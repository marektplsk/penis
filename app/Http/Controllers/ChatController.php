<?php

namespace App\Http\Controllers;

use App\Models\WinModel;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function generateFeedback()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to access feedback.'], 401);
        }

        try {
            // Gather trade data
            $trades = WinModel::where('user_id', Auth::id())->get();

            // Map the trade data
            $tradeDetails = $trades->map(function ($trade) {
                return [
                    'pair' => $trade->pair,
                    'is_win' => $trade->is_win,
                    'risk' => $trade->risk,
                    'risk_reward_ratio' => $trade->risk_reward_ratio,
                    'data' => $trade->data,
                    'trade_type' => $trade->trade_type,
                    'tags' => $trade->tags ? json_decode($trade->tags, true) : [], // Safely decode tags
                    'hour_session' => $trade->hour_session,
                ];
            });

            // Analyze emotional patterns from 'data' field
            $emotions = [];
            foreach ($trades as $trade) {
                if (str_contains($trade->data, 'stress')) {
                    $emotions[] = 'stressful';
                }
                if (str_contains($trade->data, 'confidence')) {
                    $emotions[] = 'confident';
                }
            }

            // Analyze tag performance
            $tagAnalysis = [];
            foreach ($tradeDetails as $trade) {
                foreach ($trade['tags'] as $tag) {
                    if (!isset($tagAnalysis[$tag])) {
                        $tagAnalysis[$tag] = ['wins' => 0, 'losses' => 0];
                    }
                    $trade['is_win'] ? $tagAnalysis[$tag]['wins']++ : $tagAnalysis[$tag]['losses']++;
                }
            }

            // Analyze session performance
            $sessionAnalysis = $tradeDetails->groupBy('hour_session')->map(function ($trades) {
                $wins = $trades->where('is_win', true)->count();
                $losses = $trades->where('is_win', false)->count();
                return ['wins' => $wins, 'losses' => $losses];
            });

            // Gather portfolio data
            $portfolios = Portfolio::where('user_id', Auth::id())->get();
            $portfolioSummary = [
                'total_value' => $portfolios->sum('amount'),
                'types' => $portfolios->groupBy('type')->map->sum('amount'),
            ];

            // Construct the prompt for ChatGPT
            $prompt = $this->createFeedbackPrompt($tradeDetails, $portfolioSummary, $emotions, $tagAnalysis, $sessionAnalysis);

            // Send to ChatGPT
            return $this->sendToChatGPT($prompt);
        } catch (\Throwable $e) {
            Log::error('Error generating feedback: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while generating feedback.'], 500);
        }
    }

    private function createFeedbackPrompt($tradeDetails, $portfolioSummary, $emotions, $tagAnalysis, $sessionAnalysis)
    {
        $prompt = "You are a professional trading analyst familiar with ICT trading concepts. Based on the provided data, give the user concise feedback on their performance, including statistics. Make it personal and focus on trading terms. Don’t explain the tags—assume the user knows them. Also make it structured and more simplified.\n\n";

        // Statistical feedback based on data
        $totalTrades = $tradeDetails->count();
        $wins = $tradeDetails->where('is_win', true)->count();
        $losses = $totalTrades - $wins;
        $winRate = $wins / $totalTrades;

        $prompt .= "Out of $totalTrades trades, you won $wins and lost $losses, with a win rate of " . round($winRate * 100, 2) . "%. ";

        // Risk Management Insights
        $losingTrades = $tradeDetails->where('is_win', false);
        $highestRiskLosingTrade = $losingTrades->sortByDesc('risk')->first();
        if ($highestRiskLosingTrade) {
            // Assuming we have an average risk value from winning trades
            $averageWinningRisk = $tradeDetails->where('is_win', true)->avg('risk');

            // Calculate the percentage difference between the highest risk losing trade and average winning risk
            $riskDifference = $highestRiskLosingTrade['risk'] - $averageWinningRisk;

            // Calculate dynamic improvement estimate
            $improvementPercentage = ($riskDifference / $highestRiskLosingTrade['risk']) * 100;

            // Ensure improvement percentage is reasonable
            $improvementPercentage = min(max($improvementPercentage, 0), 100);

            // Add to prompt
            $prompt .= "In your last losing trade, you risked {$highestRiskLosingTrade['risk']}. Reducing your risk to a more typical level could improve your chances of success by " . round($improvementPercentage, 2) . "%.\n";
        }

        // Tag Performance Insights
        foreach ($tagAnalysis as $tag => $data) {
            $tagWins = $data['wins'] ?? 0;
            $tagLosses = $data['losses'] ?? 0;
            if ($tagWins + $tagLosses > 0) {
                $tagWinRate = $tagWins / ($tagWins + $tagLosses);
                if ($tagWinRate <= 0.5) {
                    $prompt .= "The $tag tag has a lower win rate of " . round($tagWinRate * 100, 2) . "%. It may be worth analyzing its effectiveness further. ";
                }
            }
        }

        // Session Performance Insights
        foreach ($sessionAnalysis as $session => $stats) {
            $sessionWinRate = $stats['wins'] / ($stats['wins'] + $stats['losses']);
            if ($sessionWinRate > 0.5) {
                $prompt .= "You perform well during the $session session, with a win rate of " . round($sessionWinRate * 100, 2) . "%. ";
            }
        }

        // Portfolio Insights
        $totalPortfolioValue = $portfolioSummary['total_value'] ?? 0;
        $portfolioGrowth = $portfolioSummary['growth'] ?? 0;
        $prompt .= "Your total portfolio value is $$totalPortfolioValue. Over the last period, your portfolio has grown by " . round($portfolioGrowth * 100, 2) . "%.";

        // Overall Summary and Closing
        $prompt .= "\nOverall, you're doing well in some areas, particularly in session performance (e.g., NY AM). To improve, focus on reducing risk, analyzing low-performing tags, and diversifying your portfolio.";

        return $prompt;
    }


    private function sendToChatGPT($prompt)
    {
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('CHAT_GPT_KEY')
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-4",
                "messages" => [
                    [
                        "role" => "user",
                        "content" => $prompt
                    ]
                ],
                "temperature" => 0.7,
                "max_tokens" => 1024
            ])->json();

            return response()->json($response['choices'][0]['message']['content']);
        } catch (\Throwable $e) {
            Log::error('Error connecting to ChatGPT: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch feedback from ChatGPT.'], 500);
        }
    }
}

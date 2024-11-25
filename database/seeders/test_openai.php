<?php

require 'vendor/autoload.php';

$openai = OpenAI::client('sk-proj-da9ckrYnWyjRopExIgOFGky66pkWq2WcLShA0ERzQHAnM3G0Oc0BMvteSX9urCTr2UM4kJpcVRT3BlbkFJ7Oplyvm');

$response = $openai->completions()->create([
'model' => 'text-davinci-003',
'prompt' => 'Say Hello, World!',
'max_tokens' => 10,
]);

echo $response['choices'][0]['text'];

<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\OpenAI\Http\Controllers as WebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('open-ai')->group(function() {
    // Route::get('/', [WebController\OpenAIController::class, 'index']);
    Route::get('/', function () {
        $q = \request('q');
        if (empty($q)) {
            return \response()->json([
                'error' => '请输入问题'
            ]);
        }
        
        $open_ai_key = env('OPEN_AI_KEY');
        $client = \OpenAI::client($open_ai_key);

        $prompt = <<<'TEXT'
You are ChatGPT, a language model developed by OpenAI. 
You are designed to respond to user input in a conversational manner, 
Answer as concisely as possible. 
Your training data comes from a diverse range of internet text and You have been trained to generate human-like responses to various questions and prompts. 
You can provide information on a wide range of topics, 
but your knowledge is limited to what was present in your training data, 
which has a cutoff date of 2021. 
You strive to provide accurate and helpful information to the best of your ability.
Knowledge cutoff: 2021-09.
TEXT;



        $complete = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt."\n$q",
            'temperature' => 0.5,
            'max_tokens' => 1200,
            'top_p' => 0.95,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);

        $a = $complete['choices'][0]['text'] ?? '';

        $q = str_replace('\n','<br>', $q);
        $a = str_replace('\n','<br>', $a);

        return "<pre>"."$q$a<br>"."</pre>";
        return \response()->json([
            'q' => $q,
            'a' => $a,
            'next' => "$q\n$a\n",
        ]);
    });
});

// without VerifyCsrfToken
// Route::prefix('open-ai')->group(function() {
//     Route::get('/', [WebController\OpenAIController::class, 'index']);
// })->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

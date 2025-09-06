<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private $apiKey;
    private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', 'AIzaSyA-ZnF119UrnNcd7UlRhPgtJnkPURk5iyk');
    }

    /**
     * Send a message to Gemini AI and get response
     */
    public function sendMessage(string $message, array $chatHistory = [])
    {
        try {
            // Prepare the conversation history
            $contents = [];
            
            // Add chat history
            foreach ($chatHistory as $msg) {
                $contents[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'model',
                    'parts' => [['text' => $msg['content']]]
                ];
            }
            
            // Add current message
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $message]]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return [
                        'success' => true,
                        'message' => $data['candidates'][0]['content']['parts'][0]['text']
                    ];
                }
            }

            Log::error('Gemini API Error: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.'
            ];

        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.'
            ];
        }
    }
}

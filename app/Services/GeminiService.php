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
     * Send a message to Gemini AI and get response with user context
     */
    public function sendMessage(string $message, array $chatHistory = [], array $userContext = [])
    {
        try {
            // Build personalized system instruction
            $systemInstruction = $this->buildSystemInstruction($userContext);
            
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

            $requestData = [
                'contents' => $contents,
                'systemInstruction' => [
                    'parts' => [['text' => $systemInstruction]]
                ],
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
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, $requestData);

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

    /**
     * Build personalized system instruction based on user context
     */
    private function buildSystemInstruction(array $userContext): string
    {
        $basePrompt = "You are FitHabs AI, a knowledgeable and supportive fitness and wellness coach. Your role is to provide personalized, evidence-based advice on health, fitness, nutrition, and wellness. Always prioritize user safety, motivation, and practical guidance.";
        
        if (empty($userContext)) {
            return $basePrompt;
        }

        $userDetails = [];
        
        // Basic user information
        if (!empty($userContext['name'])) {
            $userDetails[] = "Name: " . $userContext['name'];
        }
        
        if (!empty($userContext['age'])) {
            $userDetails[] = "Age: " . $userContext['age'] . " years old";
        }
        
        if (!empty($userContext['gender'])) {
            $userDetails[] = "Gender: " . ucfirst($userContext['gender']);
        }
        
        // Physical stats
        if (!empty($userContext['weight']) && !empty($userContext['weight_unit'])) {
            $userDetails[] = "Weight: " . $userContext['weight'] . " " . $userContext['weight_unit'];
        }
        
        if (!empty($userContext['height']) && !empty($userContext['height_unit'])) {
            $userDetails[] = "Height: " . $userContext['height'] . " " . $userContext['height_unit'];
        }
        
        // Goals and preferences
        if (!empty($userContext['goal'])) {
            $goalMap = [
                'lose_weight' => 'Lose weight',
                'gain_weight' => 'Gain weight', 
                'maintain_weight' => 'Maintain current weight',
                'build_muscle' => 'Build muscle'
            ];
            $goalText = $goalMap[$userContext['goal']] ?? ucfirst(str_replace('_', ' ', $userContext['goal']));
            $userDetails[] = "Primary Goal: " . $goalText;
        }
        
        if (!empty($userContext['activity_level'])) {
            $activityMap = [
                'sedentary' => 'Sedentary (little to no exercise)',
                'light' => 'Light activity (light exercise 1-3 days/week)',
                'moderate' => 'Moderate activity (moderate exercise 3-5 days/week)',
                'very_active' => 'Very active (intense exercise 6-7 days/week)'
            ];
            $activityText = $activityMap[$userContext['activity_level']] ?? ucfirst(str_replace('_', ' ', $userContext['activity_level']));
            $userDetails[] = "Activity Level: " . $activityText;
        }
        
        // Daily goals
        if (!empty($userContext['daily_calorie_goal'])) {
            $userDetails[] = "Daily Calorie Goal: " . $userContext['daily_calorie_goal'] . " calories";
        }
        
        if (!empty($userContext['daily_steps_goal'])) {
            $userDetails[] = "Daily Steps Goal: " . number_format($userContext['daily_steps_goal']) . " steps";
        }
        
        if (!empty($userContext['daily_water_goal'])) {
            $userDetails[] = "Daily Water Goal: " . $userContext['daily_water_goal'] . " liters";
        }
        
        // Dietary preferences
        if (!empty($userContext['dietary_preferences'])) {
            $preferences = is_array($userContext['dietary_preferences']) 
                ? implode(', ', $userContext['dietary_preferences'])
                : $userContext['dietary_preferences'];
            $userDetails[] = "Dietary Preferences: " . $preferences;
        }
        
        if (!empty($userContext['allergies'])) {
            $allergies = is_array($userContext['allergies']) 
                ? implode(', ', $userContext['allergies'])
                : $userContext['allergies'];
            $userDetails[] = "Allergies: " . $allergies;
        }
        
        if (!empty($userContext['meal_types'])) {
            $mealTypes = is_array($userContext['meal_types']) 
                ? implode(', ', $userContext['meal_types'])
                : $userContext['meal_types'];
            $userDetails[] = "Preferred Meal Types: " . $mealTypes;
        }
        
        // Cooking preferences
        if (!empty($userContext['cooking_time_preference'])) {
            $userDetails[] = "Cooking Time Preference: " . $userContext['cooking_time_preference'];
        }
        
        if (!empty($userContext['serving_preference'])) {
            $userDetails[] = "Serving Preference: " . $userContext['serving_preference'];
        }

        // Additional goals from UserGoal model
        if (!empty($userContext['user_goals'])) {
            $goals = $userContext['user_goals'];
            if (!empty($goals['steps'])) {
                $userDetails[] = "Steps Goal: " . number_format($goals['steps']) . " steps";
            }
            if (!empty($goals['calories'])) {
                $userDetails[] = "Calorie Goal: " . $goals['calories'] . " calories";
            }
            if (!empty($goals['water'])) {
                $userDetails[] = "Water Goal: " . $goals['water'] . " liters";
            }
        }

        if (!empty($userDetails)) {
            $userInfo = "Use the following user details to personalize your responses:\n" . implode("\n", $userDetails);
            return $basePrompt . "\n\n" . $userInfo . "\n\nAlways provide personalized, relevant advice based on this information. Be encouraging, supportive, and focus on practical, achievable recommendations. Remember to prioritize safety and suggest consulting healthcare professionals when appropriate.";
        }

        return $basePrompt;
    }
}

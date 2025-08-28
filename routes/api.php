<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetTokenController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\CacheLockController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobBatchController;
use App\Http\Controllers\FailedJobController;
use App\Http\Controllers\PersonalAccessTokenController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\DailyActivityController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\UserWorkoutController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\MealPlanRecipeController;
use App\Http\Controllers\UserMealPlanController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\EducationContentController;
use App\Http\Controllers\UserFavoriteController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AiChatMessageController;
use App\Http\Controllers\SearchLogController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserAchievementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

        // Sanctum CSRF cookie route for token-based authentication
        Route::get('/sanctum/csrf-cookie', [\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show']);

// API version 1 routes
Route::prefix('v1')->group(function () {

    // Public routes (no authentication required)
    // Route::middleware(['cache.control', 'throttle:60,1'])->group(function () {
        Route::get('/test', function () {
            return response()->json(['message' => 'API is working!']);
        });
        
        Route::apiResource('workouts', WorkoutController::class)->only(['index', 'show']);
        Route::get('workouts/filter', [WorkoutController::class, 'filter']);
        Route::apiResource('recipes', RecipeController::class)->only(['index', 'show']);
        Route::get('recipes/search', [RecipeController::class, 'search']);
        Route::apiResource('meal-plans', MealPlanController::class)->only(['index', 'show']);
        Route::get('meal-plans/filter', [MealPlanController::class, 'filter']);
        Route::apiResource('education-contents', EducationContentController::class)->only(['index', 'show']);
        Route::get('education-contents/search', [EducationContentController::class, 'search']);
    // });

    //  Admin user api routes
    Route::post('/admin-login ', [AuthController::class, 'adminLogin']);


    // Authentication routes (public)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/login/google', [AuthController::class, 'googleLogin']);
    Route::post('/login/apple', [AuthController::class, 'appleLogin']);

    // Password reset token routes (public for reset functionality)
    Route::apiResource('password-reset-tokens', PasswordResetTokenController::class)->only(['store', 'show', 'destroy']);

    // Authenticated routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // Authentication routes
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // User routes
        Route::apiResource('users', UserController::class);
        Route::apiResource('daily-activities', DailyActivityController::class);
        Route::apiResource('user-workouts', UserWorkoutController::class);
        Route::apiResource('user-meal-plans', UserMealPlanController::class);
        Route::apiResource('user-progress', UserProgressController::class);
        Route::apiResource('user-favorites', UserFavoriteController::class)->only(['index', 'store', 'destroy']);
        Route::apiResource('user-settings', UserSettingController::class);
        Route::apiResource('notifications', NotificationController::class);
        Route::apiResource('user-achievements', UserAchievementController::class);

        // Chat routes with professional access
        Route::middleware(['professional.access'])->group(function () {
            Route::apiResource('chats', ChatController::class);
            Route::apiResource('chat-messages', ChatMessageController::class)->middleware('throttle:100,1');
        });

        // AI Chat routes
        Route::apiResource('ai-chats', AiChatController::class);
        Route::apiResource('ai-chat-messages', AiChatMessageController::class)->middleware('throttle:100,1');

        // Search log routes with rate limiting
        Route::apiResource('search-logs', SearchLogController::class)->middleware('throttle:60,1');
        Route::get('search-logs/recent', [SearchLogController::class, 'recentSearches']);

        // System-related routes
        Route::apiResource('sessions', SessionController::class);
        Route::apiResource('cache', CacheController::class);
        Route::apiResource('cache-locks', CacheLockController::class);
        Route::apiResource('jobs', JobController::class);
        Route::apiResource('job-batches', JobBatchController::class);
        Route::apiResource('failed-jobs', FailedJobController::class);
        Route::apiResource('personal-access-tokens', PersonalAccessTokenController::class);
    });

    // Admin routes (restricted to specific roles)
    Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
        Route::apiResource('admin-users', AdminUserController::class);
    });

    // Content creation routes (restricted to editor or super_admin)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('workouts', WorkoutController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('exercises', ExerciseController::class);
        Route::apiResource('recipes', RecipeController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('meal-plans', MealPlanController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('meal-plan-recipes', MealPlanRecipeController::class);
        Route::apiResource('education-contents', EducationContentController::class)->only(['store', 'update', 'destroy']);
    });

    // Professional routes (restricted to respective professional types)
    Route::middleware(['auth:sanctum', 'validate.json'])->group(function () {
        Route::apiResource('coaches', CoachController::class);
        Route::apiResource('clinics', ClinicController::class);
        Route::apiResource('therapists', TherapistController::class);
    });
});
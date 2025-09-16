<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Recipe;
use App\Models\Workout;
use App\Models\MealPlan;
use App\Models\EducationContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get financial metrics
            $financialMetrics = $this->getFinancialMetrics();
            
            // Get user metrics
            $userMetrics = $this->getUserMetrics();
            
            // Get content metrics
            $contentMetrics = $this->getContentMetrics();
            
            // Get recent users
            $recentUsers = $this->getRecentUsers();
            
            // Calculate summary
            $summary = $this->getSummary($financialMetrics, $userMetrics, $contentMetrics);
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'financial_metrics' => $financialMetrics,
                    'user_metrics' => $userMetrics,
                    'content_metrics' => $contentMetrics,
                    'recent_users' => $recentUsers,
                    'summary' => $summary
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getFinancialMetrics()
    {
        // Calculate total earnings (this would be from your payment/subscription system)
        $totalEarned = $this->calculateTotalEarnings();
        
        // Calculate change from last month
        $lastMonthEarnings = $this->calculateLastMonthEarnings();
        $changePercentage = $lastMonthEarnings > 0 
            ? (($totalEarned - $lastMonthEarnings) / $lastMonthEarnings) * 100 
            : 0;
        
        return [
            'total_earned' => [
                'value' => $totalEarned,
                'formatted' => '$' . number_format($totalEarned, 2),
                'change_percentage' => round($changePercentage, 1),
                'change_text' => $changePercentage >= 0 
                    ? '+' . round($changePercentage, 1) . '% from last month'
                    : round($changePercentage, 1) . '% from last month',
                'trend' => $changePercentage >= 0 ? 'up' : 'down'
            ]
        ];
    }

    private function getUserMetrics()
    {
        $totalUsers = User::count();
        $lastMonthUsers = User::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $changePercentage = $lastMonthUsers > 0 
            ? (($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100 
            : 0;
        
        return [
            'total_users' => [
                'value' => $totalUsers,
                'formatted' => '+' . number_format($totalUsers),
                'change_percentage' => round($changePercentage, 1),
                'change_text' => $changePercentage >= 0 
                    ? '+' . round($changePercentage, 1) . '% from last month'
                    : round($changePercentage, 1) . '% from last month',
                'trend' => $changePercentage >= 0 ? 'up' : 'down'
            ]
        ];
    }

    private function getContentMetrics()
    {
        // Meal Plans
        $totalMealPlans = MealPlan::count();
        $lastMonthMealPlans = MealPlan::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $mealPlansChangePercentage = $lastMonthMealPlans > 0 
            ? (($totalMealPlans - $lastMonthMealPlans) / $lastMonthMealPlans) * 100 
            : 0;
        
        // Workouts
        $totalWorkouts = Workout::count();
        $lastHourWorkouts = Workout::where('created_at', '>=', Carbon::now()->subHour())->count();
        $workoutsChangePercentage = $lastHourWorkouts > 0 
            ? (($totalWorkouts - $lastHourWorkouts) / $lastHourWorkouts) * 100 
            : 0;
        
        return [
            'meal_plans' => [
                'value' => $totalMealPlans,
                'formatted' => '+' . number_format($totalMealPlans),
                'change_percentage' => round($mealPlansChangePercentage, 1),
                'change_text' => $mealPlansChangePercentage >= 0 
                    ? '+' . round($mealPlansChangePercentage, 1) . '% from last month'
                    : round($mealPlansChangePercentage, 1) . '% from last month',
                'trend' => $mealPlansChangePercentage >= 0 ? 'up' : 'down'
            ],
            'workouts_created' => [
                'value' => $totalWorkouts,
                'formatted' => '+' . number_format($totalWorkouts),
                'change_percentage' => round($workoutsChangePercentage, 1),
                'change_text' => $workoutsChangePercentage >= 0 
                    ? '+' . round($workoutsChangePercentage, 1) . ' since last hour'
                    : round($workoutsChangePercentage, 1) . ' since last hour',
                'trend' => $workoutsChangePercentage >= 0 ? 'up' : 'down'
            ]
        ];
    }

    private function getRecentUsers()
    {
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name ?? 'User ' . $user->id,
                    'email' => $user->email,
                    'initials' => $this->generateInitials($user->name ?? $user->email),
                    'avatar_color' => $this->generateAvatarColor($user->id),
                    'earnings' => $this->calculateUserEarnings($user->id),
                    'earnings_formatted' => '+$' . number_format($this->calculateUserEarnings($user->id), 2),
                    'signup_date' => $user->created_at
                ];
            });

        return [
            'title' => 'Recent Users',
            'subtitle' => 'Recent users signed up',
            'users' => $recentUsers
        ];
    }

    private function getSummary($financialMetrics, $userMetrics, $contentMetrics)
    {
        return [
            'total_revenue' => $financialMetrics['total_earned']['value'],
            'total_users' => $userMetrics['total_users']['value'],
            'total_meal_plans' => $contentMetrics['meal_plans']['value'],
            'total_workouts' => $contentMetrics['workouts_created']['value'],
            'recent_users_count' => 3
        ];
    }

    private function calculateTotalEarnings()
    {
        // This would integrate with your payment system
        // For now, returning a calculated value based on users
        $totalUsers = User::count();
        
        // Assuming average subscription price of $15.99/month per user
        // This is a placeholder calculation until subscription system is implemented
        $averageMonthlyRevenue = $totalUsers * 15.99;
        
        return $averageMonthlyRevenue;
    }

    private function calculateLastMonthEarnings()
    {
        // Calculate earnings from last month
        $lastMonthUsers = User::where('created_at', '>=', Carbon::now()->subMonth())
            ->where('created_at', '<', Carbon::now()->subMonth()->addMonth())
            ->count();
        
        return $lastMonthUsers * 15.99; // Average subscription price
    }

    private function calculateUserEarnings($userId)
    {
        // This would integrate with your payment/subscription system
        // For now, returning a random value for demonstration
        return rand(39, 1999);
    }

    private function generateInitials($name)
    {
        if (!$name) return 'U';
        
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }

    private function generateAvatarColor($userId)
    {
        $colors = [
            '#3B82F6', // Blue
            '#10B981', // Green
            '#F59E0B', // Yellow
            '#EF4444', // Red
            '#8B5CF6', // Purple
            '#06B6D4', // Cyan
            '#F97316', // Orange
            '#84CC16', // Lime
        ];
        
        return $colors[$userId % count($colors)];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MealPlan;
use App\Models\Recipe;
use App\Models\MealPlanRecipe;
use App\Models\AdminUser;

class MealPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user if none exists
        $adminUser = AdminUser::firstOrCreate([
            'email' => 'admin@fithabs.com'
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password123'),
            'is_active' => true
        ]);

        // Create sample recipes
        $recipes = [
            // Breakfast recipes
            [
                'name' => 'Oatmeal with Berries',
                'description' => 'Healthy oatmeal topped with fresh berries and honey',
                'image_url' => 'https://images.unsplash.com/photo-1517686469429-8bdb88b9f907?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'breakfast',
                'prep_time_minutes' => 5,
                'cook_time_minutes' => 10,
                'servings' => 1,
                'calories_per_serving' => 250,
                'protein_per_serving' => 8.5,
                'carbs_per_serving' => 45.2,
                'fat_per_serving' => 4.1,
                'fiber_per_serving' => 6.8,
                'sugar_per_serving' => 12.3,
                'ingredients' => '1 cup rolled oats, 1 cup water, 1/2 cup mixed berries, 1 tbsp honey, 1/4 cup almond milk',
                'instructions' => 'Cook oats with water for 10 minutes, top with berries and honey, serve with almond milk',
                'dietary_tags' => ['vegetarian', 'gluten_free'],
                'allergen_info' => ['nuts'],
                'difficulty' => 'easy',
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            [
                'name' => 'Greek Yogurt Parfait',
                'description' => 'Creamy Greek yogurt layered with granola and fresh fruits',
                'image_url' => 'https://images.unsplash.com/photo-1488477181946-6428a02819d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'breakfast',
                'prep_time_minutes' => 5,
                'cook_time_minutes' => 0,
                'servings' => 1,
                'calories_per_serving' => 180,
                'protein_per_serving' => 15.2,
                'carbs_per_serving' => 22.1,
                'fat_per_serving' => 3.8,
                'fiber_per_serving' => 4.2,
                'sugar_per_serving' => 18.5,
                'ingredients' => '1 cup Greek yogurt, 1/4 cup granola, 1/2 cup mixed berries, 1 tbsp honey',
                'instructions' => 'Layer yogurt, granola, and berries in a glass, drizzle with honey',
                'dietary_tags' => ['vegetarian'],
                'allergen_info' => ['dairy', 'nuts'],
                'difficulty' => 'easy',
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            [
                'name' => 'Avocado Toast',
                'description' => 'Whole grain toast topped with mashed avocado and microgreens',
                'image_url' => 'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'breakfast',
                'prep_time_minutes' => 8,
                'cook_time_minutes' => 3,
                'servings' => 1,
                'calories_per_serving' => 220,
                'protein_per_serving' => 6.8,
                'carbs_per_serving' => 28.5,
                'fat_per_serving' => 12.3,
                'fiber_per_serving' => 8.9,
                'sugar_per_serving' => 2.1,
                'ingredients' => '2 slices whole grain bread, 1 ripe avocado, 1 tbsp olive oil, microgreens, salt and pepper',
                'instructions' => 'Toast bread, mash avocado with olive oil, spread on toast, top with microgreens',
                'dietary_tags' => ['vegetarian', 'vegan'],
                'allergen_info' => [],
                'difficulty' => 'easy',
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            // Lunch recipes
            [
                'name' => 'Quinoa Salad Bowl',
                'description' => 'Nutritious quinoa salad with roasted vegetables and lemon dressing',
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'lunch',
                'prep_time_minutes' => 15,
                'cook_time_minutes' => 20,
                'servings' => 2,
                'calories_per_serving' => 320,
                'protein_per_serving' => 12.5,
                'carbs_per_serving' => 45.8,
                'fat_per_serving' => 8.9,
                'fiber_per_serving' => 9.2,
                'sugar_per_serving' => 6.8,
                'ingredients' => '1 cup quinoa, 2 cups mixed vegetables, 2 tbsp olive oil, 1 lemon, herbs, salt and pepper',
                'instructions' => 'Cook quinoa, roast vegetables, combine with lemon dressing and herbs',
                'dietary_tags' => ['vegetarian', 'vegan', 'gluten_free'],
                'allergen_info' => [],
                'difficulty' => 'medium',
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            [
                'name' => 'Grilled Chicken Salad',
                'description' => 'Fresh mixed greens with grilled chicken breast and balsamic vinaigrette',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'lunch',
                'prep_time_minutes' => 10,
                'cook_time_minutes' => 15,
                'servings' => 1,
                'calories_per_serving' => 280,
                'protein_per_serving' => 35.2,
                'carbs_per_serving' => 8.5,
                'fat_per_serving' => 12.8,
                'fiber_per_serving' => 6.2,
                'sugar_per_serving' => 4.1,
                'ingredients' => '1 chicken breast, 3 cups mixed greens, 1/4 cup cherry tomatoes, 2 tbsp balsamic vinaigrette',
                'instructions' => 'Grill chicken, assemble salad with greens and tomatoes, drizzle with vinaigrette',
                'dietary_tags' => ['high_protein'],
                'allergen_info' => [],
                'difficulty' => 'medium',
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            // Dinner recipes
            [
                'name' => 'Salmon with Roasted Vegetables',
                'description' => 'Baked salmon fillet with seasonal roasted vegetables',
                'image_url' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'dinner',
                'prep_time_minutes' => 15,
                'cook_time_minutes' => 25,
                'servings' => 2,
                'calories_per_serving' => 380,
                'protein_per_serving' => 42.5,
                'carbs_per_serving' => 18.9,
                'fat_per_serving' => 16.2,
                'fiber_per_serving' => 7.8,
                'sugar_per_serving' => 8.5,
                'ingredients' => '2 salmon fillets, 3 cups mixed vegetables, 2 tbsp olive oil, herbs, lemon, salt and pepper',
                'instructions' => 'Season salmon, roast vegetables, bake salmon for 20-25 minutes',
                'dietary_tags' => ['high_protein', 'omega_3'],
                'allergen_info' => ['fish'],
                'difficulty' => 'medium',
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            [
                'name' => 'Vegetarian Pasta Primavera',
                'description' => 'Whole grain pasta with fresh spring vegetables and light cream sauce',
                'image_url' => 'https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'dinner',
                'prep_time_minutes' => 20,
                'cook_time_minutes' => 15,
                'servings' => 2,
                'calories_per_serving' => 420,
                'protein_per_serving' => 15.8,
                'carbs_per_serving' => 65.2,
                'fat_per_serving' => 12.5,
                'fiber_per_serving' => 8.9,
                'sugar_per_serving' => 9.2,
                'ingredients' => '8 oz whole grain pasta, 3 cups mixed vegetables, 1/2 cup light cream, 2 tbsp olive oil, herbs',
                'instructions' => 'Cook pasta, sauté vegetables, combine with cream sauce and herbs',
                'dietary_tags' => ['vegetarian'],
                'allergen_info' => ['dairy', 'gluten'],
                'difficulty' => 'medium',
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            // Snack recipes
            [
                'name' => 'Apple with Almond Butter',
                'description' => 'Fresh apple slices with creamy almond butter',
                'image_url' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'snack',
                'prep_time_minutes' => 5,
                'cook_time_minutes' => 0,
                'servings' => 1,
                'calories_per_serving' => 150,
                'protein_per_serving' => 4.2,
                'carbs_per_serving' => 22.5,
                'fat_per_serving' => 6.8,
                'fiber_per_serving' => 4.8,
                'sugar_per_serving' => 16.2,
                'ingredients' => '1 medium apple, 2 tbsp almond butter',
                'instructions' => 'Slice apple, serve with almond butter',
                'dietary_tags' => ['vegetarian', 'vegan', 'gluten_free'],
                'allergen_info' => ['nuts'],
                'difficulty' => 'easy',
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            [
                'name' => 'Greek Yogurt with Berries',
                'description' => 'Creamy Greek yogurt topped with fresh mixed berries',
                'image_url' => 'https://images.unsplash.com/photo-1488477181946-6428a02819d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meal_type' => 'snack',
                'prep_time_minutes' => 3,
                'cook_time_minutes' => 0,
                'servings' => 1,
                'calories_per_serving' => 120,
                'protein_per_serving' => 18.5,
                'carbs_per_serving' => 15.2,
                'fat_per_serving' => 2.1,
                'fiber_per_serving' => 3.8,
                'sugar_per_serving' => 12.5,
                'ingredients' => '1 cup Greek yogurt, 1/2 cup mixed berries',
                'instructions' => 'Top yogurt with fresh berries',
                'dietary_tags' => ['vegetarian', 'high_protein'],
                'allergen_info' => ['dairy'],
                'difficulty' => 'easy',
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ]
        ];

        // Create recipes
        $createdRecipes = [];
        foreach ($recipes as $recipeData) {
            $recipe = Recipe::create($recipeData);
            $createdRecipes[] = $recipe;
        }

        // Create meal plans
        $mealPlans = [
            [
                'name' => 'Vegetarian Weight Loss Plan',
                'description' => '7-day vegetarian meal plan designed for weight loss with balanced nutrition',
                'image_url' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'duration_days' => 7,
                'goals' => ['weight_loss'],
                'dietary_preferences' => ['vegetarian'],
                'allergen_free' => ['nuts', 'dairy'],
                'target_calories_min' => 1500,
                'target_calories_max' => 1800,
                'difficulty' => 'easy',
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            [
                'name' => 'High Protein Muscle Building',
                'description' => '14-day meal plan focused on protein-rich foods for muscle building',
                'image_url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'duration_days' => 14,
                'goals' => ['build_muscle'],
                'dietary_preferences' => ['high_protein'],
                'allergen_free' => [],
                'target_calories_min' => 2200,
                'target_calories_max' => 2500,
                'difficulty' => 'medium',
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            [
                'name' => 'Gluten-Free Wellness Plan',
                'description' => '7-day gluten-free meal plan promoting overall wellness and energy',
                'image_url' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'duration_days' => 7,
                'goals' => ['maintain_weight', 'improve_energy'],
                'dietary_preferences' => ['gluten_free'],
                'allergen_free' => ['gluten'],
                'target_calories_min' => 1800,
                'target_calories_max' => 2100,
                'difficulty' => 'easy',
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ],
            [
                'name' => 'Vegan Energy Boost',
                'description' => '7-day vegan meal plan designed to boost energy and vitality',
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'duration_days' => 7,
                'goals' => ['improve_energy'],
                'dietary_preferences' => ['vegan'],
                'allergen_free' => ['dairy', 'eggs'],
                'target_calories_min' => 1600,
                'target_calories_max' => 1900,
                'difficulty' => 'medium',
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => $adminUser->id
            ]
        ];

        // Create meal plans and assign recipes
        foreach ($mealPlans as $index => $mealPlanData) {
            $mealPlan = MealPlan::create($mealPlanData);
            
            // Assign recipes to meal plan (different recipes for different days)
            $recipeIndex = 0;
            for ($day = 1; $day <= $mealPlan->duration_days; $day++) {
                // Breakfast
                MealPlanRecipe::create([
                    'meal_plan_id' => $mealPlan->id,
                    'recipe_id' => $createdRecipes[0]->id, // Oatmeal
                    'day_number' => $day,
                    'meal_type' => 'breakfast',
                    'servings' => 1,
                    'order' => 1
                ]);
                
                // Lunch
                MealPlanRecipe::create([
                    'meal_plan_id' => $mealPlan->id,
                    'recipe_id' => $createdRecipes[3]->id, // Quinoa Salad
                    'day_number' => $day,
                    'meal_type' => 'lunch',
                    'servings' => 1,
                    'order' => 1
                ]);
                
                // Dinner
                MealPlanRecipe::create([
                    'meal_plan_id' => $mealPlan->id,
                    'recipe_id' => $createdRecipes[5]->id, // Salmon
                    'day_number' => $day,
                    'meal_type' => 'dinner',
                    'servings' => 1,
                    'order' => 1
                ]);
                
                // Snack
                MealPlanRecipe::create([
                    'meal_plan_id' => $mealPlan->id,
                    'recipe_id' => $createdRecipes[7]->id, // Apple with Almond Butter
                    'day_number' => $day,
                    'meal_type' => 'snack',
                    'servings' => 1,
                    'order' => 1
                ]);
            }
        }

        $this->command->info('✅ Meal plans and recipes seeded successfully!');
        $this->command->info('📊 Created ' . count($createdRecipes) . ' recipes');
        $this->command->info('📊 Created ' . count($mealPlans) . ' meal plans');
    }
}

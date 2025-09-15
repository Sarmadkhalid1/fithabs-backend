<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Map polymorphic types for favorites
        Relation::morphMap([
            'workout' => \App\Models\Workout::class,
            'education_content' => \App\Models\EducationContent::class,
        ]);
    }
}

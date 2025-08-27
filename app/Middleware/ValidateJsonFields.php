<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJsonFields
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jsonFields = [
            'specializations', 'certifications', 'services', 'equipment_needed',
            'tags', 'dietary_tags', 'allergen_info', 'goals', 'dietary_preferences',
            'allergen_free', 'measurements', 'attachments', 'metadata', 'filters_applied',
            'data', 'criteria', 'permissions'
        ];

        foreach ($jsonFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                if (!is_array($value) && !is_null($value)) {
                    return response()->json(['error' => ucfirst($field) . ' must be an array or null'], 422);
                }
            }
        }

        return $next($request);
    }
}
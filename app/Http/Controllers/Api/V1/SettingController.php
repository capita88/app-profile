<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Setting::query();

        if ($request->filled('group')) {
            $query->where('group', $request->query('group'));
        }

        $settings = $query->get();

        // Mengembalikan format list dan juga key-value dictionary untuk kemudahan frontend Flutter
        $mapped = [];
        foreach ($settings as $setting) {
            $mapped[$setting->key] = match ($setting->type) {
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        }

        return response()->json([
            'data' => SettingResource::collection($settings),
            'dictionary' => $mapped,
        ]);
    }
}


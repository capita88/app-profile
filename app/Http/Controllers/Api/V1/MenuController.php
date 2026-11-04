<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function show(string $location): JsonResponse
    {
        $menu = Menu::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->with(['rootItems.children'])
            ->first();

        if (! $menu) {
            return response()->json([
                'data' => null,
                'message' => "Menu untuk lokasi {$location} tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'data' => new MenuResource($menu),
        ]);
    }
}


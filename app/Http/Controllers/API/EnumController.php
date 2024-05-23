<?php

namespace App\Http\Controllers\API;

use App\Enums\RentStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class EnumController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'List of enums',
            'data' => [
                'rent_status' => RentStatusEnum::cases(),
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\JsonResponse;

class LanguagesController extends Controller
{

    public function index(): JsonResponse
    {
        $languages = Language::select(['name', 'code'])->orderBy('name')->get();

        return response()->json($languages);
    }
}

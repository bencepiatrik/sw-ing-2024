<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        // Retrieve all news entries
        return response()->json(News::all());
    }

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index']);
    }

}

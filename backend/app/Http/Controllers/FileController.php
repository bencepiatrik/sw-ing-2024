<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Response;

class FileController extends Controller
{
    public function download($fileName)
    {
        // Cesta k súboru v storage
        $filePath = "public/works/{$fileName}";

        // Overenie, či súbor existuje
        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Vráti súbor so správnou hlavičkou na stiahnutie
        return Storage::download($filePath, $fileName);
    }
}
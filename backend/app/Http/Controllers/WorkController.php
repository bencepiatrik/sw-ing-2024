<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class WorkController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Validácia vstupných dát
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'files' => 'nullable|array|max:3', // Maximálne 3 súbory
            'files.*' => 'file|max:10240', // Maximálna veľkosť jedného súboru je 10MB
        ]);

        // Vytvorenie novej práce
        $work = Work::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
            'status' => 'submitted',
        ]);

        // Ukladanie súborov, ak existujú
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $filePaths = []; // Inicializuj pole na cesty súborov
        
            foreach ($files as $file) {
                $filePath = $file->store('works', 'public'); // Ulož súbor
                $filePaths[] = $filePath; // Ulož cestu do poľa
            }
        
            // Ulož pole súborov ako JSON do stĺpca "files"
            $work->files = json_encode($filePaths);
            $work->save();
        }
        

        // Vrátenie odpovede s vytvorenou prácou
        return response()->json([
            'message' => 'Work created successfully',
            'work' => $work,
        ], 201);
    }

    public function getUserWorksByCategory($categoryId)
    {
        $userId = auth()->id();
    
        $works = Work::where('category_id', $categoryId)
            ->where('user_id', $userId)
            ->get();
    
        return response()->json($works);
    }
    
    public function index($categoryId)
    {
        $works = Work::where('category_id', $categoryId)
            ->where('user_id', auth()->id())
            ->get()
            ->map(function ($work) {
                $work->files = json_decode($work->files, true) ?? []; // Dekóduj JSON
                return $work;
            });
    
        return response()->json($works);
    }
}

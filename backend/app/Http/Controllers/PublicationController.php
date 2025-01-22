<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $conferenceId = $request->query('conference_id');

        if (!$conferenceId) {
            return response()->json(['error' => 'Conference ID is required'], 400);
        }

        $publications = Publication::where('conference_id', $conferenceId)->get();

        return response()->json($publications);
    }

    public function show($id): JsonResponse
    {
        $publication = Publication::find($id);

        if (!$publication) {
            return response()->json(['message' => 'Publication not found'], 404);
        }

        return response()->json($publication);
    }

    public function update(Request $request, $id)
    {
        logger()->info('Prijaté údaje:', $request->all());
        logger()->info('Súbory:', $request->file());
        // Validácia vstupu
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'abstract' => 'required|string',
                'keywords' => 'required|string',
                'conference_id' => 'required|integer|exists:conferences,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger()->error('Validácia zlyhala:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        }
        logger()->info('Validácia prešla, údaje:', $validated);
        // Nájdeme publikáciu pre úpravu
        $publication = Publication::where('id', $id)
            ->where('user_id', auth()->id()) // Overenie vlastníctva
            ->firstOrFail();
    
        // Aktualizácia údajov publikácie
        $publication->update($validated);
    
        // Spracovanie súborov, ak boli pridané nové
        if ($request->hasFile('files')) {
            $newFiles = [];
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('publications', 'public');
                $newFiles[] = $filePath;
            }
        
            $existingFiles = json_decode($publication->files, true) ?? [];
            $publication->files = json_encode(array_merge($existingFiles, $newFiles));
            $publication->save();
        }
    
        return response()->json([
            'message' => 'Publikácia bola úspešne aktualizovaná.',
            'publication' => $publication,
        ]);
    
    }

    public function removeFile(Request $request, $id)
    {
        // Validácia vstupu
        $validated = $request->validate([
            'file_path' => 'required|string',
        ]);

        // Nájdeme publikáciu
        $publication = Publication::where('id', $id)
            ->where('user_id', auth()->id()) // Overenie vlastníctva
            ->firstOrFail();

        $files = json_decode($publication->files, true) ?? [];
        $filePath = $validated['file_path'];

        // Odstránenie súboru zo zoznamu a zo storage
        if (($key = array_search($filePath, $files)) !== false) {
            unset($files[$key]);
            Storage::delete('public/' . $filePath); // Odstránenie súboru zo storage
        }

        // Aktualizácia zoznamu súborov
        $publication->files = json_encode(array_values($files));
        $publication->save();

        return response()->json([
            'message' => 'Súbor bol úspešne odstránený.',
            'publication' => $publication,
        ]);
    }

    public function store(Request $request)
    {
        // Validácia vstupu
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'conference_id' => 'required|integer|exists:conferences,id',
        ]);

        // Vytvorenie novej publikácie
        $publication = Publication::create([
            'title' => $request->title,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'conference_id' => $request->conference_id,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Publikácia bola úspešne vytvorená.',
            'publication' => $publication,
        ], 201);
    }
}

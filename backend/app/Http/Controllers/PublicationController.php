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

        return response()->json([
            'id' => $publication->id,
            'title' => $publication->title,
            'abstract' => $publication->abstract,
            'keywords' => $publication->keywords,
            'conference_id' => $publication->conference_id,
            'file' => $publication->file, // Stored path
            'file_name' => $publication->file_name, // Original filename
        ]);
    }


    public function update(Request $request, $id)
    {
        // Validate the JSON payload
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'conference_id' => 'required|integer|exists:conferences,id',
            'set_status' => 'nullable|string|in:odovzdaná', // Optional status change
        ]);

        // Find the publication by ID
        $publication = Publication::findOrFail($id);

        // Update the publication fields
        $publication->update([
            'title' => $validated['title'],
            'abstract' => $validated['abstract'],
            'keywords' => $validated['keywords'],
            'conference_id' => $validated['conference_id'],
            'status' => $validated['set_status'] ?? $publication->status, // Update status if provided
        ]);

        return response()->json([
            'message' => 'Publication updated successfully.',
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
        // Validate request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'conference_id' => 'required|integer|exists:conferences,id',
            'file' => 'nullable|file|mimes:pdf,txt|max:10240', // Optional file
        ]);

        // Handle file upload if provided
        $filePath = null;
        $originalFileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('publications', 'public');
            $originalFileName = $file->getClientOriginalName(); // Get the original filename
        }

        // Create a new publication record
        $publication = Publication::create([
            'title' => $validated['title'],
            'abstract' => $validated['abstract'],
            'keywords' => $validated['keywords'],
            'conference_id' => $validated['conference_id'],
            'user_id' => auth()->id(), // Assuming logged-in user
            'file' => $filePath, // Stored file path
            'file_name' => $originalFileName, // Original file name
        ]);

        return response()->json([
            'message' => 'Publication created successfully.',
            'publication' => $publication,
        ]);
    }


    public function upload(Request $request, $id)
    {
        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:pdf,txt|max:10240',
        ]);

        // Find the publication by ID
        $publication = Publication::findOrFail($id);

        // Delete old file if it exists
        if ($publication->file) {
            Storage::disk('public')->delete($publication->file);
        }

        // Save the new file
        $file = $request->file('file');
        $filePath = $file->store('publications', 'public');
        $originalFileName = $file->getClientOriginalName(); // Get the original filename

        // Update the file path and original filename in the publication record
        $publication->file = $filePath;
        $publication->file_name = $originalFileName;
        $publication->save();

        return response()->json([
            'message' => 'File uploaded successfully.',
            'file' => $filePath,
            'original_file_name' => $originalFileName,
        ]);
    }




}

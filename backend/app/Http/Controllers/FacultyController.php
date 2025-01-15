<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function getByUniversity(Request $request)
    {
        $universityId = $request->query('uni_id');
        $faculties = Faculty::where('uni_id', $universityId)->get();
        return response()->json($faculties);
    }
}

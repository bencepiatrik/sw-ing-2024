<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function getByFaculty(Request $request)
    {
        $facultyId = $request->query('faculty_id');
        return Department::where('faculty_id', $facultyId)->get();
    }}

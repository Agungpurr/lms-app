<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoursePublicController extends Controller
{
    public function index()
    {
        return view('courses.index');
    }

    public function show($id)
    {
        return view('courses.show');
    }
}
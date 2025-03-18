<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class AdminController extends Controller
{
    public function dashboard()
    {
        $students = Student::with('user')->get();
        return view('admin.dashboard', compact('students'));
    }

    public function students()
    {
        $students = Student::with('user')->get();
        return view('admin.students', compact('students'));
    }
}

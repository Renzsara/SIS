<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Enrollment;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalSubjects = Subject::count();
        $totalEnrollments = Enrollment::count();
        $totalGrades = Grade::count();
        $recentActivities = []; // Fetch recent activities from your database

        return view('admin.dashboard', compact('totalStudents', 'totalSubjects', 'totalEnrollments', 'totalGrades', 'recentActivities'));
    }

    public function students()
    {
        $students = Student::with('user')->get();
        return view('admin.students', compact('students'));
    }

    public function grades()
    {
        $grades = Grade::with(['student.user', 'subject'])->get();
        return view('admin.grades', compact('grades'));
    }
}

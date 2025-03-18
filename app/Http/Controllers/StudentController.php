<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user()->student;
        return view('student.dashboard', compact('student'));
    }

    public function index()
    {
        $students = Student::with('user')->get();
        return view('admin.students', compact('students'));
    }

    public function getStudents()
    {
        $students = Student::with('user');
        return DataTables::of($students)
            ->addColumn('name', function ($student) {
                return $student->user->name;
            })
            ->addColumn('email', function ($student) {
                return $student->user->email;
            })
            ->addColumn('actions', function ($student) {
                return view('admin.students.actions', compact('student'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'student_id' => 'required|string|unique:students,student_id',
            'course' => 'required|string',
            'year' => 'required|string',
            'section' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'course' => $request->course,
                'year' => $request->year,
                'section' => $request->section,
            ]);

            DB::commit();
            return redirect()->route('admin.students')
                ->with('success', 'Student created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'course' => 'required|string',
            'year' => 'required|string',
            'section' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $student->update([
                'student_id' => $request->student_id,
                'course' => $request->course,
                'year' => $request->year,
                'section' => $request->section,
            ]);

            DB::commit();
            return redirect()->route('admin.students')
                ->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }

    public function destroy(Student $student)
    {
        try {
            $student->user->delete(); // This will cascade delete the student record
            return redirect()->route('admin.students')
                ->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }
}

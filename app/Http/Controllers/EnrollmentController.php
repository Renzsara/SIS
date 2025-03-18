<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['student.user', 'subject'])->get();
        $students = Student::with('user')->get();
        $subjects = Subject::all();
        return view('admin.enrollments', compact('enrollments', 'students', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'school_year' => 'required|string',
            'semester' => 'required|in:1st,2nd,Summer',
        ]);

        try {
            // Check for existing enrollment
            $exists = Enrollment::where('student_id', $request->student_id)
                ->where('subject_id', $request->subject_id)
                ->where('school_year', $request->school_year)
                ->where('semester', $request->semester)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Student is already enrolled in this subject for the selected semester and school year.');
            }

            Enrollment::create($request->all());
            return redirect()->route('admin.enrollments')
                ->with('success', 'Enrollment created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating enrollment: ' . $e->getMessage());
        }
    }

    public function destroy(Enrollment $enrollment)
    {
        try {
            $enrollment->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'school_year' => 'required|string',
            'semester' => 'required|in:1st,2nd,Summer',
        ]);

        try {
            // Check for existing enrollment excluding current record
            $exists = Enrollment::where('student_id', $request->student_id)
                ->where('subject_id', $request->subject_id)
                ->where('school_year', $request->school_year)
                ->where('semester', $request->semester)
                ->where('id', '!=', $enrollment->id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Student is already enrolled in this subject for the selected semester and school year.');
            }

            $enrollment->update($request->all());
            return redirect()->route('admin.enrollments')
                ->with('success', 'Enrollment updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating enrollment: ' . $e->getMessage());
        }
    }
}

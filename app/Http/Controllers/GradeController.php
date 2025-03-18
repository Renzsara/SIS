<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::with(['student.user', 'subject'])->get();
        $students = Student::with('user')->get();
        $subjects = Subject::all();

        return view('admin.grades', compact('grades', 'students', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'midterm_grade' => 'required|numeric|in:1.00,1.25,1.50,1.75,2.00,2.25,2.50,2.75,3.00,5.00',
            'final_grade' => 'required|numeric|in:1.00,1.25,1.50,1.75,2.00,2.25,2.50,2.75,3.00,5.00',
            'status' => 'required|in:Regular,Incomplete,FDA,Withdrawn',
            'remarks' => 'nullable|string',
        ]);

        try {
            $grade = Grade::where('student_id', $request->student_id)
                         ->where('subject_id', $request->subject_id)
                         ->firstOrFail();
                         
            $grade->update([
                'midterm_grade' => $request->midterm_grade,
                'final_grade' => $request->final_grade,
                'status' => $request->status,
                'remarks' => $request->remarks
            ]);
            
            $grade->calculateAverageGrade();
            
            return redirect()->route('admin.grades')
                ->with('success', 'Grade updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating grade: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'midterm_grade' => 'required|numeric|in:1.00,1.25,1.50,1.75,2.00,2.25,2.50,2.75,3.00,5.00',
            'final_grade' => 'required|numeric|in:1.00,1.25,1.50,1.75,2.00,2.25,2.50,2.75,3.00,5.00',
            'status' => 'required|in:Regular,Incomplete,FDA,Withdrawn',
            'remarks' => 'nullable|string',
        ]);

        try {
            $grade->update($request->all());
            $grade->calculateAverageGrade();
            
            return redirect()->route('admin.grades')
                ->with('success', 'Grade updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating grade: ' . $e->getMessage());
        }
    }

    public function destroy(Grade $grade)
    {
        try {
            $grade->delete();
            return redirect()->route('admin.grades')
                ->with('success', 'Grade deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting grade: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return view('admin.subjects', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|string|unique:subjects,subject_code',
            'subject_name' => 'required|string',
            'units' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            Subject::create($request->all());
            return redirect()->route('admin.subjects')
                ->with('success', 'Subject created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating subject: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_code' => 'required|string|unique:subjects,subject_code,' . $subject->id,
            'subject_name' => 'required|string',
            'units' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $subject->update($request->all());
            return redirect()->route('admin.subjects')
                ->with('success', 'Subject updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating subject: ' . $e->getMessage());
        }
    }

    public function destroy(Subject $subject)
    {
        try {
            $subject->delete();
            return redirect()->route('admin.subjects')
                ->with('success', 'Subject deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting subject: ' . $e->getMessage());
        }
    }
}

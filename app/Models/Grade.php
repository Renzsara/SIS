<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'midterm_grade',
        'final_grade',
        'average_grade',
        'status',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, ['student_id', 'subject_id'], ['student_id', 'subject_id']);
    }

    public function calculateAverageGrade()
    {
        if ($this->midterm_grade !== null && $this->final_grade !== null) {
            $average = ($this->midterm_grade + $this->final_grade) / 2;
            $this->average_grade = $this->roundToNearestGrade($average);
            $this->save();
        }
    }

    private function roundToNearestGrade($grade)
    {
        $validGrades = [1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00];
        $closest = null;
        foreach ($validGrades as $validGrade) {
            if ($closest === null || abs($grade - $closest) > abs($validGrade - $grade)) {
                $closest = $validGrade;
            }
        }
        return $closest;
    }
}

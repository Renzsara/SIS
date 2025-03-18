<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'school_year',
        'semester'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->hasOne(Grade::class, 'subject_id', 'subject_id')
                    ->where('student_id', $this->student_id);
    }
}

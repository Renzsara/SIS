@extends('layouts.studentLayout')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Dashboard</h1>
    </div>

    <!-- Student Info Card -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Student Information</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ Auth::user()->name }}
                            </div>
                            <div class="mt-2">
                                Student ID: {{ Auth::user()->student->student_id }}<br>
                                Course: {{ Auth::user()->student->course }}<br>
                                Year: {{ Auth::user()->student->year }}<br>
                                Section: {{ Auth::user()->student->section }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Subjects and Grades -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Enrolled Subjects and Grades</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="gradesTable">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Units</th>
                            <th>Midterm</th>
                            <th>Finals</th>
                            <th>Average</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->subject->subject_code }}</td>
                            <td>{{ $enrollment->subject->subject_name }}</td>
                            <td>{{ $enrollment->subject->units }}</td>
                            <td>{{ optional($enrollment->grade)->midterm_grade ?? 'N/A' }}</td>
                            <td>{{ optional($enrollment->grade)->final_grade ?? 'N/A' }}</td>
                            <td>{{ optional($enrollment->grade)->average_grade ?? 'N/A' }}</td>
                            <td>{{ optional($enrollment->grade)->status ?? 'Pending' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#gradesTable').DataTable({
            "ordering": true,
            "info": true,
            "paging": true,
            "searching": true
        });
    });
</script>
@endpush
@endsection
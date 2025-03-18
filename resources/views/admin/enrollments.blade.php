@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Enrollments Management</h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addEnrollmentModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Enrollment
        </button>
    </div>

    @include('partials.alerts')

    <!-- Enrollments List Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Enrollments List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="enrollmentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>School Year</th>
                            <th>Semester</th>
                            <th style="width: 120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->student->student_id }}</td>
                            <td>{{ $enrollment->student->user->name }}</td>
                            <td>{{ $enrollment->subject->subject_code }}</td>
                            <td>{{ $enrollment->subject->subject_name }}</td>
                            <td>{{ $enrollment->school_year }}</td>
                            <td>{{ $enrollment->semester }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary btn-sm edit-enrollment" 
                                            data-id="{{ $enrollment->id }}"
                                            data-student="{{ $enrollment->student_id }}"
                                            data-subject="{{ $enrollment->subject_id }}"
                                            data-school-year="{{ $enrollment->school_year }}"
                                            data-semester="{{ $enrollment->semester }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-enrollment" data-id="{{ $enrollment->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Enrollment Modal -->
<div class="modal fade" id="addEnrollmentModal" tabindex="-1" role="dialog" aria-labelledby="addEnrollmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEnrollmentModalLabel">Add New Enrollment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addEnrollmentForm" method="POST" action="{{ route('admin.enrollments.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select class="form-control" id="student_id" name="student_id" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject_id">Subject</label>
                        <select class="form-control" id="subject_id" name="subject_id" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="school_year">School Year</label>
                        <input type="text" class="form-control" id="school_year" name="school_year" required>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Enrollment Modal -->
<div class="modal fade" id="editEnrollmentModal" tabindex="-1" role="dialog" aria-labelledby="editEnrollmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEnrollmentModalLabel">Edit Enrollment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editEnrollmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_student_id">Student</label>
                        <select class="form-control" id="edit_student_id" name="student_id" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_subject_id">Subject</label>
                        <select class="form-control" id="edit_subject_id" name="subject_id" required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_school_year">School Year</label>
                        <input type="text" class="form-control" id="edit_school_year" name="school_year" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_semester">Semester</label>
                        <select class="form-control" id="edit_semester" name="semester" required>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#enrollmentsTable').DataTable({
            "order": [[0, "asc"]], 
            "pageLength": 10,
            "responsive": true
        });

        // Delete Enrollment
        $(document).on('click', '.delete-enrollment', function() {
            if (confirm('Are you sure you want to delete this enrollment?')) {
                let id = $(this).data('id');
                let url = '/admin/enrollments/' + id;
                
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            }
        });

        // Edit Enrollment
        $(document).on('click', '.edit-enrollment', function() {
            let id = $(this).data('id');
            $('#editEnrollmentForm').attr('action', '/admin/enrollments/' + id);
            $('#edit_student_id').val($(this).data('student'));
            $('#edit_subject_id').val($(this).data('subject'));
            $('#edit_school_year').val($(this).data('school-year'));
            $('#edit_semester').val($(this).data('semester'));
            $('#editEnrollmentModal').modal('show');
        });

        $.ajaxSetup({
            error: function(xhr, status, error) {
                let errorMessage = 'An error occurred: ' + error;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${errorMessage}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                
                $('.card').first().prepend(alertHtml);
            }
        });
    });
</script>
@endpush
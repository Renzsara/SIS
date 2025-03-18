@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Grades Management</h1>
    </div>

    @include('partials.alerts')

    <!-- Grades List Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Grades List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="gradesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Midterm</th>
                            <th>Finals</th>
                            <th>Average</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th style="width: 120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                        <tr>
                            <td>{{ $grade->student->user->name }}</td>
                            <td>{{ $grade->subject->subject_name }}</td>
                            <td>{{ $grade->midterm_grade ?? 'N/A' }}</td>
                            <td>{{ $grade->final_grade ?? 'N/A' }}</td>
                            <td>{{ $grade->average_grade ?? 'N/A' }}</td>
                            <td>{{ $grade->status }}</td>
                            <td>{{ $grade->remarks }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary btn-sm edit-grade" 
                                            data-id="{{ $grade->id }}"
                                            data-toggle="modal" 
                                            data-target="#editGradeModal">
                                        <i class="fas fa-edit"></i>
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

<!-- Edit Grade Modal -->
<div class="modal fade" id="editGradeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Grade</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editGradeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Midterm Grade</label>
                        <select class="form-control" name="midterm_grade" required>
                            <option value="">Select Grade</option>
                            @foreach([1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00] as $grade)
                                <option value="{{ $grade }}">{{ number_format($grade, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Final Grade</label>
                        <select class="form-control" name="final_grade" required>
                            <option value="">Select Grade</option>
                            @foreach([1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00] as $grade)
                                <option value="{{ $grade }}">{{ number_format($grade, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" required>
                            <option value="Regular">Regular</option>
                            <option value="Incomplete">Incomplete</option>
                            <option value="FDA">FDA</option>
                            <option value="Withdrawn">Withdrawn</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#gradesTable').DataTable({
            "order": [[0, "asc"]],
            "pageLength": 10,
            "responsive": true
        });

        // Edit Grade
        $('.edit-grade').click(function() {
            var id = $(this).data('id');
            $('#editGradeForm').attr('action', '/admin/grades/' + id);
            
            // Add AJAX call to get current grade data and populate the form
            $.get('/admin/grades/' + id, function(data) {
                $('select[name="midterm_grade"]').val(data.midterm_grade);
                $('select[name="final_grade"]').val(data.final_grade);
                $('select[name="status"]').val(data.status);
                $('textarea[name="remarks"]').val(data.remarks);
            });
        });

        // Delete Grade
        $(document).on('click', '.delete-grade', function() {
            let id = $(this).data('id');
            $('#deleteGradeForm').attr('action', '/admin/grades/' + id);
            $('#deleteGradeModal').modal('show');
        });
    });
</script>
@endpush

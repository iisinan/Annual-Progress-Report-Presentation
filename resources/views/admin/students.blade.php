<x-app-layout>
    <x-slot name="header">
        Registered Students
    </x-slot>
    <x-slot name="actions">
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="fa-solid fa-user-plus me-2"></i> Add Student
        </button>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary-acetel fw-bold">Student Directory</h5>
            <form action="{{ route('admin.students') }}" method="GET" class="d-flex align-items-center">
                <label for="sessionFilter" class="me-2 fw-semibold mb-0">Session:</label>
                <select name="session" id="sessionFilter" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="all" {{ $session === 'all' ? 'selected' : '' }}>All Sessions</option>
                    @foreach($sessions as $s)
                        @if($s)
                            <option value="{{ $s }}" {{ $session === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endif
                    @endforeach
                    @if(!in_array($currentSession, $sessions->toArray()))
                        <option value="{{ $currentSession }}" {{ $session === $currentSession ? 'selected' : '' }}>{{ $currentSession }}</option>
                    @endif
                </select>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="studentsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Matric Number</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Programme</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>{{ $student->matric_number }}</td>
                            <td>{{ $student->user->name ?? 'Unknown (Deleted User)' }}</td>
                            <td>{{ $student->user->email ?? 'Unknown' }}</td>
                            <td>{{ $student->programme->name }}</td>
                            <td>{{ $student->department->name }}</td>
                            <td>
                                @if($student->presentation && $student->presentation->file_path)
                                    <span class="badge bg-success">PDF Uploaded</span>
                                @else
                                    <span class="badge bg-warning">Pending PDF</span>
                                @endif
                                
                                @if($student->schedule)
                                    <span class="badge bg-info">Scheduled</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary" title="View Profile">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student and all their records?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Student">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No students found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary-acetel text-white">
                        <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="matric_number" class="form-label">Matric Number</label>
                                <input type="text" class="form-control" id="matric_number" name="matric_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="academic_session" class="form-label">Academic Session</label>
                                <select class="form-select" id="academic_session" name="academic_session" required>
                                    <option value="">Select Session</option>
                                    @php $currentYear = date('Y'); @endphp
                                    @for($year = $currentYear; $year >= 2020; $year--)
                                        <option value="{{ $year }}/{{ $year+1 }}">{{ $year }}/{{ $year+1 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year_of_admission" class="form-label">Year of Admission</label>
                                <select class="form-select" id="year_of_admission" name="year_of_admission" required>
                                    <option value="">Select Year</option>
                                    @for($year = $currentYear; $year >= 2010; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="intake" class="form-label">Intake Batch</label>
                                <select class="form-select" id="intake" name="intake" required>
                                    <option value="">Select Intake</option>
                                    <option value="1">Intake 1 (First Semester)</option>
                                    <option value="2">Intake 2 (Second Semester)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-select" id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="programme_id" class="form-label">Programme</label>
                                <select class="form-select" id="programme_id" name="programme_id" required>
                                    <option value="">Select Programme</option>
                                    @foreach($programmes as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="supervisor_name" class="form-label">Supervisor Name</label>
                            <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="research_title" class="form-label">Research Title</label>
                            <textarea class="form-control" id="research_title" name="research_title" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="presentation_title" class="form-label">Abstract</label>
                            <textarea class="form-control" id="presentation_title" name="presentation_title" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="current_research_stage" class="form-label">Current Research Stage</label>
                            <select class="form-select" id="current_research_stage" name="current_research_stage" required>
                                <option value="">Select Stage</option>
                                <option value="Proposal">Proposal</option>
                                <option value="Data Collection">Data Collection</option>
                                <option value="Data Analysis">Data Analysis</option>
                                <option value="Writing">Writing</option>
                                <option value="Final Review">Final Review</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary bg-primary-acetel">Create Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable();
        });
    </script>
    @endpush
</x-app-layout>

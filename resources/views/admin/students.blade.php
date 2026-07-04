<x-app-layout>
    <x-slot name="header">
        Registered Students
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
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->user->email }}</td>
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

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable();
        });
    </script>
    @endpush
</x-app-layout>

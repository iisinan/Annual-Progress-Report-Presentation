<x-app-layout>
    <x-slot name="header">Supervisor: {{ $supervisor->name }}</x-slot>

    <div class="page-title-bar mb-4">
        <div>
            <h1><i class="fa-solid fa-chalkboard-user me-2"></i> {{ $supervisor->name }}</h1>
            <small style="color:rgba(255,255,255,0.75);">{{ $supervisor->email }} &mdash; {{ $supervisor->supervisees->count() }} student(s)</small>
        </div>
        <a href="{{ route('admin.supervisors.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);border-radius:8px;">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header py-3" style="background:var(--acetel-green-pale); border-bottom:2px solid var(--acetel-green);">
            <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-users me-2"></i>Assigned Students</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:rgba(26,122,50,0.06);">
                        <tr>
                            <th>Matric No.</th>
                            <th>Student Name</th>
                            <th>Programme</th>
                            <th>Department</th>
                            <th class="text-center">PDF Uploaded</th>
                            <th class="text-center">Approval Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supervisor->supervisees as $student)
                        <tr>
                            <td class="fw-semibold">{{ $student->matric_number }}</td>
                            <td>{{ $student->user->name ?? 'N/A' }}</td>
                            <td>{{ $student->programme->name ?? 'N/A' }}</td>
                            <td>{{ $student->department->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($student->presentation && $student->presentation->file_path)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $status = $student->pivot->status ?? 'pending'; @endphp
                                @if($status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No students assigned to this supervisor.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

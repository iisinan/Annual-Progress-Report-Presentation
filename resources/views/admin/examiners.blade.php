<x-app-layout>
    <x-slot name="header">
        Manage Examiners
    </x-slot>

    <x-slot name="actions">
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addExaminerModal">
            <i class="fa-solid fa-user-plus me-2"></i> Add Examiner
        </button>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="examinersTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examiners as $examiner)
                        <tr>
                            <td>{{ $examiner->name }}</td>
                            <td>{{ $examiner->email }}</td>
                            <td>{{ $examiner->created_at->format('d M Y') }}</td>
                            <td>
                                <form action="{{ route('admin.examiners.destroy', $examiner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this examiner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Examiner"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No examiners found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Examiner Modal -->
    <div class="modal fade" id="addExaminerModal" tabindex="-1" aria-labelledby="addExaminerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.examiners.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary-acetel text-white">
                        <h5 class="modal-title" id="addExaminerModalLabel">Add New Examiner</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary bg-primary-acetel">Create Examiner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#examinersTable').DataTable();
        });
    </script>
    @endpush
</x-app-layout>

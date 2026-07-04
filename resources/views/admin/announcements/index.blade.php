<x-app-layout>
    <x-slot name="header">
        Announcements Board
    </x-slot>

    <div class="row">
        <!-- Create Announcement Form -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Post New Announcement</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.announcements.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-bullhorn me-2"></i> Post Announcement</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List of Announcements -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Recent Announcements</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Content Snippet</th>
                                    <th>Posted By</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($announcements as $announcement)
                                <tr>
                                    <td class="fw-bold">{{ $announcement->title }}</td>
                                    <td>{{ Str::limit($announcement->content, 50) }}</td>
                                    <td>{{ $announcement->creator->name }}</td>
                                    <td>{{ $announcement->created_at->format('d M, Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form method="POST" action="{{ route('admin.announcements.toggle', $announcement) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $announcement->is_active ? 'btn-success' : 'btn-secondary' }}" title="Toggle Visibility">
                                                    <i class="fa-solid {{ $announcement->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Announcement"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No announcements posted yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

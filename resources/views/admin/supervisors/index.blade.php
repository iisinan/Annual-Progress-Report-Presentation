<x-app-layout>
    <x-slot name="header">Manage Supervisors</x-slot>

    <div class="page-title-bar mb-4">
        <div>
            <h1><i class="fa-solid fa-chalkboard-user me-2"></i> Supervisor Accounts</h1>
            <small style="color:rgba(255,255,255,0.75);">All supervisor accounts and their assigned students</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark me-2"></i>{{ session('error') }}</div>
    @endif

    {{-- Potential Duplicates Alert --}}
    @if(count($potentialDuplicates) > 0)
    <div class="card mb-4 border-0" style="border-left: 4px solid #f59e0b !important; background:rgba(245,158,11,0.07);">
        <div class="card-body py-3 px-4">
            <h6 class="fw-bold mb-3" style="color:#f59e0b;"><i class="fa-solid fa-triangle-exclamation me-2"></i>Potential Duplicate Accounts Detected</h6>
            <p class="text-muted mb-3" style="font-size:0.88rem;">The following supervisors appear to have the same name but different email addresses. Use the Merge tool below to consolidate them into a single account.</p>

            @foreach($potentialDuplicates as $group)
            <div class="p-3 rounded mb-3" style="background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.3);">
                <div class="fw-bold mb-2" style="color:#f59e0b; font-size:0.9rem;">
                    <i class="fa-solid fa-user me-1"></i> {{ $group->first()->name }}
                    <span class="badge ms-2" style="background:#f59e0b; color:#000;">{{ $group->count() }} accounts</span>
                </div>
                <div class="row g-2">
                    @foreach($group as $sup)
                    <div class="col-md-6">
                        <div class="p-2 rounded d-flex justify-content-between align-items-center" style="background:rgba(0,0,0,0.15);">
                            <div>
                                <div class="fw-semibold" style="font-size:0.85rem;">{{ $sup->email }}</div>
                                <small class="text-muted">{{ $sup->supervisees_count }} student(s)</small>
                            </div>
                            <span class="badge bg-secondary">ID: {{ $sup->id }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- Quick merge form --}}
                @if($group->count() === 2)
                <form method="POST" action="{{ route('admin.supervisors.merge') }}" class="mt-3 merge-form" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="primary_id" value="{{ $group->first()->id }}">
                    <input type="hidden" name="duplicate_id" value="{{ $group->last()->id }}">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <small class="text-muted">Keep: <strong>{{ $group->first()->email }}</strong> and merge <strong>{{ $group->last()->email }}</strong> into it</small>
                        <button type="submit" class="btn btn-sm btn-warning fw-bold" data-msg="Merge '{{ $group->last()->email }}' into '{{ $group->first()->email }}'? This cannot be undone.">
                            <i class="fa-solid fa-code-merge me-1"></i> Merge Now
                        </button>
                    </div>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- All Supervisors Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header py-3" style="background:var(--acetel-green-pale); border-bottom:2px solid var(--acetel-green);">
            <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-list me-2"></i>All Supervisor Accounts ({{ $supervisors->count() }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="supervisorsTable">
                    <thead style="background:rgba(26,122,50,0.06);">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Students</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supervisors as $sup)
                        <tr>
                            <td class="fw-semibold">{{ $sup->name }}</td>
                            <td>{{ $sup->email }}</td>
                            <td class="text-center">
                                <span class="badge {{ $sup->supervisees_count > 0 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $sup->supervisees_count }}
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:0.85rem;">{{ $sup->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.supervisors.show', $sup->id) }}" class="btn btn-sm btn-outline-primary" title="View Students">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.users.reset-password', $sup->id) }}" method="POST" class="reset-pw-form d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning" data-name="{{ $sup->name }}" title="Reset Password">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No supervisor accounts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Manual Merge Tool --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header py-3" style="background:rgba(239,68,68,0.06); border-bottom:2px solid #ef4444;">
            <h6 class="m-0 fw-bold text-danger"><i class="fa-solid fa-code-merge me-2"></i>Manual Account Merge Tool</h6>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3" style="font-size:0.88rem;">
                Select a <strong>Primary</strong> account (to keep) and a <strong>Duplicate</strong> account (to delete). All students from the duplicate will be reassigned to the primary account, and the duplicate will be permanently removed.
            </p>
            <form method="POST" action="{{ route('admin.supervisors.merge') }}" class="merge-form" onsubmit="return false;">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Primary Account <span class="text-muted fw-normal">(keep this)</span></label>
                        <select name="primary_id" class="form-select" required>
                            <option value="">-- Select supervisor --</option>
                            @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }} &lt;{{ $sup->email }}&gt; ({{ $sup->supervisees_count }} students)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Duplicate Account <span class="text-muted fw-normal">(delete this)</span></label>
                        <select name="duplicate_id" class="form-select" required>
                            <option value="">-- Select duplicate --</option>
                            @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }} &lt;{{ $sup->email }}&gt; ({{ $sup->supervisees_count }} students)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-danger w-100" data-msg="Are you sure? This will permanently delete the duplicate account and move all its students to the primary. This cannot be undone.">
                            <i class="fa-solid fa-code-merge me-1"></i> Merge
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Safe confirm for all merge forms
        document.querySelectorAll('.merge-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var btn = form.querySelector('[data-msg]');
                var msg = btn ? btn.getAttribute('data-msg') : 'Are you sure you want to merge these accounts?';
                if (confirm(msg)) {
                    form.submit();
                }
            });
        });

        // Safe confirm for reset password
        document.querySelectorAll('.reset-pw-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var name = form.querySelector('[data-name]').getAttribute('data-name');
                if (confirm("Reset password for " + name + " to 'password'?")) {
                    form.submit();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

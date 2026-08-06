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

    {{-- ══════════════════════════════════════
         POTENTIAL DUPLICATES PANEL
    ══════════════════════════════════════ --}}
    @if(count($potentialDuplicates) > 0)
    <div class="card mb-4 border-0" style="border-left: 4px solid #f59e0b !important; background:rgba(245,158,11,0.06);">
        <div class="card-body py-3 px-4">
            <h6 class="fw-bold mb-1" style="color:#f59e0b;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                {{ count($potentialDuplicates) }} Group(s) of Duplicate Accounts Detected
            </h6>
            <p class="text-muted mb-4" style="font-size:0.85rem;">
                These supervisors appear to have the same email address.
                Select which account to <strong>keep</strong> as the primary — all students and data from the others will be moved to it, and the duplicates will be permanently deleted.
            </p>

            @foreach($potentialDuplicates as $groupIndex => $group)
            <div class="p-4 rounded mb-3" style="background:rgba(0,0,0,0.15); border:1px solid rgba(245,158,11,0.25);">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fa-solid fa-user-tag" style="color:#f59e0b;"></i>
                    <span class="fw-bold" style="color:#f59e0b;">{{ $group->first()->name }}</span>
                    <span class="badge ms-1" style="background:#f59e0b; color:#000;">{{ $group->count() }} accounts</span>
                </div>

                {{-- Accounts table --}}
                <div class="table-responsive mb-3">
                    <table class="table table-sm mb-0" style="font-size:0.85rem;">
                        <thead style="background:rgba(255,255,255,0.04);">
                            <tr>
                                <th style="width:30px;">Primary</th>
                                <th>Email</th>
                                <th class="text-center">Students</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group as $sup)
                            <tr>
                                <td class="text-center">
                                    <input type="radio"
                                           name="primary_group_{{ $groupIndex }}"
                                           value="{{ $sup->id }}"
                                           form="merge_form_{{ $groupIndex }}"
                                           class="primary-radio"
                                           data-group="{{ $groupIndex }}"
                                           {{ $loop->first ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $sup->email }}</span>
                                    @if($loop->first)
                                        <span class="badge bg-success ms-1" id="primary_badge_{{ $groupIndex }}_{{ $sup->id }}">Primary ★</span>
                                    @else
                                        <span class="badge bg-secondary ms-1 d-none" id="primary_badge_{{ $groupIndex }}_{{ $sup->id }}">Primary ★</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $sup->supervisees_count > 0 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $sup->supervisees_count }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $sup->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Hidden merge form --}}
                <form id="merge_form_{{ $groupIndex }}"
                      method="POST"
                      action="{{ route('admin.supervisors.merge-all') }}"
                      class="merge-all-form">
                    @csrf
                    {{-- primary_id will be set by JS based on selected radio --}}
                    <input type="hidden" name="primary_id" id="primary_id_{{ $groupIndex }}" value="{{ $group->first()->id }}">
                    {{-- All IDs in the group — JS will exclude the primary before submit --}}
                    @foreach($group as $sup)
                        <input type="hidden" name="all_ids[]" value="{{ $sup->id }}">
                    @endforeach
                </form>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <small class="text-muted">
                        <i class="fa-solid fa-info-circle me-1"></i>
                        Select the radio button next to the account you want to <strong>keep</strong>, then click Merge All.
                    </small>
                    <button type="button"
                            class="btn btn-warning btn-sm fw-bold merge-all-btn"
                            data-group="{{ $groupIndex }}"
                            data-count="{{ $group->count() - 1 }}">
                        <i class="fa-solid fa-code-merge me-1"></i>
                        Merge All {{ $group->count() - 1 }} Duplicate(s) Into Primary
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════
         ALL SUPERVISORS TABLE
    ══════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header py-3" style="background:var(--acetel-green-pale); border-bottom:2px solid var(--acetel-green);">
            <h6 class="m-0 fw-bold" style="color:var(--acetel-green);">
                <i class="fa-solid fa-list me-2"></i>All Supervisor Accounts ({{ $supervisors->count() }})
            </h6>
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
                                    <a href="{{ route('admin.supervisors.show', $sup->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="View Students">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.users.reset-password', $sup->id) }}"
                                          method="POST" class="reset-pw-form d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning"
                                                data-name="{{ $sup->name }}" title="Reset Password">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No supervisor accounts found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         MANUAL MERGE TOOL (any two accounts)
    ══════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header py-3" style="background:rgba(239,68,68,0.06); border-bottom:2px solid #ef4444;">
            <h6 class="m-0 fw-bold text-danger">
                <i class="fa-solid fa-code-merge me-2"></i>Manual Account Merge Tool
            </h6>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3" style="font-size:0.88rem;">
                Select a <strong>Primary</strong> account (to keep) and one or more <strong>Duplicate</strong> accounts (to delete).
                All students from the duplicates will be reassigned to the primary, and the duplicates will be permanently removed.
            </p>
            <form method="POST" action="{{ route('admin.supervisors.merge-all') }}"
                  id="manualMergeForm" class="merge-manual-form">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Primary Account <span class="text-muted fw-normal">(keep)</span></label>
                        <select name="primary_id" class="form-select" required>
                            <option value="">-- Select supervisor to keep --</option>
                            @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}">
                                {{ $sup->name }} &lt;{{ $sup->email }}&gt; ({{ $sup->supervisees_count }} students)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Duplicate Account(s) <span class="text-muted fw-normal">(delete — hold Ctrl/⌘ to pick multiple)</span></label>
                        <select name="duplicate_ids[]" class="form-select" multiple required size="5" style="height:auto;">
                            @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}">
                                {{ $sup->name }} &lt;{{ $sup->email }}&gt; ({{ $sup->supervisees_count }} students)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="manualMergeBtn"
                                class="btn btn-danger w-100"
                                data-msg="Merge selected duplicates into the primary? This is PERMANENT and cannot be undone.">
                            <i class="fa-solid fa-code-merge me-1"></i> Merge
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    // ── Radio badge update (show which is primary in duplicate groups) ──
    document.querySelectorAll('.primary-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const group = this.dataset.group;
            // Hide all badges in this group
            document.querySelectorAll('[id^="primary_badge_' + group + '_"]').forEach(b => b.classList.add('d-none'));
            // Show the selected badge
            const badge = document.getElementById('primary_badge_' + group + '_' + this.value);
            if (badge) badge.classList.remove('d-none');
            // Update hidden primary_id
            document.getElementById('primary_id_' + group).value = this.value;
        });
    });

    // ── Merge All button (auto-detect group) ──
    document.querySelectorAll('.merge-all-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const group    = this.dataset.group;
            const count    = this.dataset.count;
            const primaryId = document.getElementById('primary_id_' + group).value;
            const form     = document.getElementById('merge_form_' + group);

            if (!confirm('Merge ' + count + ' duplicate account(s) into the selected primary? This is PERMANENT and cannot be undone.')) return;

            // Build duplicate_ids[] from all_ids[] excluding the chosen primary
            const allInputs = form.querySelectorAll('input[name="all_ids[]"]');
            // Remove any old duplicate_ids inputs
            form.querySelectorAll('input[name="duplicate_ids[]"]').forEach(el => el.remove());

            allInputs.forEach(function (inp) {
                if (inp.value !== primaryId) {
                    const hidden = document.createElement('input');
                    hidden.type  = 'hidden';
                    hidden.name  = 'duplicate_ids[]';
                    hidden.value = inp.value;
                    form.appendChild(hidden);
                }
            });

            form.submit();
        });
    });

    // ── Manual merge tool ──
    document.getElementById('manualMergeBtn').addEventListener('click', function () {
        const form = document.getElementById('manualMergeForm');
        const primaryId = form.querySelector('[name="primary_id"]').value;
        const selected  = [...form.querySelectorAll('[name="duplicate_ids[]"] option:checked')];

        if (!primaryId) { alert('Please select a Primary account.'); return; }
        if (selected.length === 0) { alert('Please select at least one Duplicate account.'); return; }

        // Prevent merging into itself
        const selfSelected = selected.some(o => o.value === primaryId);
        if (selfSelected) { alert('You cannot merge an account into itself. Please deselect the primary from the duplicates list.'); return; }

        if (!confirm('Merge ' + selected.length + ' account(s) into the primary? This is PERMANENT and cannot be undone.')) return;

        form.submit();
    });

    // ── Reset password confirm ──
    document.querySelectorAll('.reset-pw-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
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

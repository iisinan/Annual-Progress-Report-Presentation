<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Supervisors') }}
        </h2>
    </x-slot>

    <style>
        .supervisors-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            padding: 3rem 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .supervisors-page::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .supervisors-page::after {
            content: '';
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .page-container {
            max-width: 760px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .page-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.025em;
            margin: 0 0 0.5rem;
        }
        .page-subtitle {
            color: #94a3b8;
            font-size: 1rem;
            margin: 0;
        }
        .info-banner {
            background: rgba(59,130,246,0.12);
            border: 1px solid rgba(59,130,246,0.3);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.75rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }
        .info-banner svg {
            flex-shrink: 0;
            margin-top: 2px;
            color: #60a5fa;
            width: 20px;
            height: 20px;
        }
        .info-banner p {
            color: #bfdbfe;
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }
        .info-banner strong { color: #fff; }
        .supervisor-card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 1.75rem 2rem;
            margin-bottom: 1.5rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .supervisor-card:hover {
            border-color: rgba(99,102,241,0.4);
            box-shadow: 0 0 30px rgba(99,102,241,0.1);
        }
        .supervisor-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            margin-bottom: 1.25rem;
            box-shadow: 0 0 14px rgba(99,102,241,0.5);
        }
        .field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }
        @media (max-width: 600px) {
            .field-grid { grid-template-columns: 1fr; }
            .page-title { font-size: 1.6rem; }
            .supervisor-card { padding: 1.25rem; }
        }
        .field-label {
            display: block;
            color: #cbd5e1;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
        }
        .styled-input {
            width: 100%;
            background: rgba(15,23,42,0.7);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: #f1f5f9;
            font-size: 0.95rem;
            padding: 0.72rem 0.85rem 0.72rem 2.6rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }
        .styled-input::placeholder { color: #475569; }
        .styled-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.25);
        }
        .styled-input.is-invalid { border-color: #f87171; }
        .field-error {
            color: #fca5a5;
            font-size: 0.78rem;
            margin-top: 0.35rem;
        }
        .submit-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.04em;
            padding: 1rem 2rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            margin-top: 0.5rem;
            box-shadow: 0 4px 24px rgba(99,102,241,0.45);
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(99,102,241,0.6);
        }
        .submit-btn:active {
            transform: translateY(0);
            opacity: 0.9;
        }
        .submit-btn svg {
            width: 20px;
            height: 20px;
        }
    </style>

    <div class="supervisors-page">
        <div class="page-container">

            {{-- Page Header --}}
            <div class="page-header">
                <h1 class="page-title">Assign Your Supervisors</h1>
                <p class="page-subtitle">Add the details of your designated academic supervisors for this report cycle.</p>
            </div>

            {{-- Info Banner --}}
            <div class="info-banner">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>
                    Based on your programme, you may assign up to <strong>{{ $maxCount }} supervisors</strong>, but you must provide at least <strong>1</strong>.
                    Please provide their correct full names and institutional email addresses.
                    The system will automatically create accounts for them and send an email invitation to review your presentation.
                </p>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.4);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;">
                    <p style="color:#fca5a5;font-weight:600;margin:0 0 0.5rem;">Please fix the following errors:</p>
                    <ul style="color:#fca5a5;font-size:0.85rem;margin:0;padding-left:1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- Form --}}
            <form method="POST" action="{{ route('student.supervisors.store') }}" id="supervisorForm">
                @csrf

                @for ($i = 0; $i < $maxCount; $i++)
                    <div class="supervisor-card" id="card_{{ $i }}">
                        <div class="supervisor-badge">
                            Supervisor {{ $i + 1 }} @if($i > 0) <span style="opacity:0.7;font-weight:400;">(Optional)</span> @endif
                        </div>

                        <div class="field-grid">
                            {{-- Name --}}
                            <div>
                                <label class="field-label" for="sup_name_{{ $i }}">Full Name with Title</label>
                                <div class="input-wrapper" style="position:relative;">
                                    <span class="input-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </span>
                                    <input
                                        id="sup_name_{{ $i }}"
                                        type="text"
                                        name="supervisors[{{ $i }}][name]"
                                        value="{{ old('supervisors.'.$i.'.name') }}"
                                        placeholder="e.g. Dr. Amina Bello"
                                        class="styled-input @error('supervisors.'.$i.'.name') is-invalid @enderror"
                                        autocomplete="off"
                                        data-index="{{ $i }}"
                                        @if($i === 0) required autofocus @endif
                                    >
                                    <div id="name_suggestions_{{ $i }}" class="autocomplete-dropdown" style="display:none;"></div>
                                </div>
                                @error('supervisors.'.$i.'.name')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="field-label" for="sup_email_{{ $i }}">Email Address</label>
                                <div class="input-wrapper" style="position:relative;">
                                    <span class="input-icon" id="email_icon_{{ $i }}">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <input
                                        id="sup_email_{{ $i }}"
                                        type="email"
                                        name="supervisors[{{ $i }}][email]"
                                        value="{{ old('supervisors.'.$i.'.email') }}"
                                        placeholder="e.g. supervisor@noun.edu.ng"
                                        class="styled-input @error('supervisors.'.$i.'.email') is-invalid @enderror"
                                        autocomplete="off"
                                        data-index="{{ $i }}"
                                        @if($i === 0) required @endif
                                    >
                                </div>
                                {{-- Live status badge --}}
                                <div id="account_status_{{ $i }}" style="margin-top:0.35rem; font-size:0.8rem; display:none;"></div>
                                @error('supervisors.'.$i.'.email')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endfor

                {{-- Duplicate warning --}}
                <div id="duplicateWarning" style="display:none; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.4); border-radius:10px; padding:0.85rem 1.1rem; margin-bottom:1rem; color:#fca5a5; font-size:0.88rem;">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    <strong>Duplicate Email:</strong> You have entered the same email address for more than one supervisor. Each supervisor must have a unique email.
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="submit-btn" id="submitBtn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Confirm &amp; Assign Supervisors
                </button>
            </form>

        </div>
    </div>

<style>
.autocomplete-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #1e293b;
    border: 1px solid rgba(99,102,241,0.4);
    border-radius: 10px;
    z-index: 999;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    overflow: hidden;
    margin-top: 4px;
}
.autocomplete-item {
    padding: 0.7rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    transition: background 0.15s;
}
.autocomplete-item:last-child { border-bottom: none; }
.autocomplete-item:hover { background: rgba(99,102,241,0.15); }
.autocomplete-item .item-name { font-weight: 600; color: #e2e8f0; font-size:0.88rem; }
.autocomplete-item .item-email { color: #94a3b8; font-size: 0.78rem; }
.status-existing { color: #34d399; }
.status-new { color: #94a3b8; }
</style>

<script>
const SEARCH_URL = "{{ route('student.supervisors.search') }}";
const maxCount = {{ $maxCount }};
let searchTimers = {};
let currentXhrs = {};

function debounce(fn, delay, key) {
    clearTimeout(searchTimers[key]);
    searchTimers[key] = setTimeout(fn, delay);
}

function setAccountStatus(index, type, message) {
    const el = document.getElementById('account_status_' + index);
    el.style.display = 'block';
    if (type === 'existing') {
        el.innerHTML = '<i class="fa-solid fa-circle-check me-1" style="color:#34d399;"></i><span style="color:#34d399;">' + message + '</span>';
    } else if (type === 'new') {
        el.innerHTML = '<i class="fa-solid fa-circle-plus me-1" style="color:#94a3b8;"></i><span style="color:#94a3b8;">' + message + '</span>';
    } else {
        el.innerHTML = '';
        el.style.display = 'none';
    }
}

function closeSuggestions(index) {
    const el = document.getElementById('name_suggestions_' + index);
    if (el) el.style.display = 'none';
}

function fillFromSuggestion(index, name, email) {
    document.getElementById('sup_name_' + index).value = name;
    document.getElementById('sup_email_' + index).value = email;
    closeSuggestions(index);
    setAccountStatus(index, 'existing', 'Existing account found — will be reused automatically.');
    checkDuplicates();
}

function searchSupervisors(query, index, targetField) {
    const dropdown = document.getElementById('name_suggestions_' + index);
    if (query.length < 2) {
        dropdown.style.display = 'none';
        return;
    }

    if (currentXhrs[index]) currentXhrs[index].abort();

    const xhr = new XMLHttpRequest();
    currentXhrs[index] = xhr;
    xhr.open('GET', SEARCH_URL + '?q=' + encodeURIComponent(query), true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const results = JSON.parse(xhr.responseText);
            if (results.length === 0) {
                dropdown.style.display = 'none';
                return;
            }
            dropdown.innerHTML = results.map(function (r) {
                return '<div class="autocomplete-item" onclick="fillFromSuggestion(' + index + ', ' + JSON.stringify(r.name) + ', ' + JSON.stringify(r.email) + ')">' +
                    '<div class="item-name">' + r.name + '</div>' +
                    '<div class="item-email">' + r.email + '</div>' +
                '</div>';
            }).join('');
            dropdown.style.display = 'block';
        }
    };
    xhr.send();
}

function checkEmailExists(email, index) {
    if (!email || email.length < 5 || !email.includes('@')) {
        setAccountStatus(index, 'clear', '');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', SEARCH_URL + '?q=' + encodeURIComponent(email), true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const results = JSON.parse(xhr.responseText);
            const match = results.find(r => r.email.toLowerCase() === email.toLowerCase());
            if (match) {
                // Auto-fill name if empty
                const nameInput = document.getElementById('sup_name_' + index);
                if (!nameInput.value.trim()) {
                    nameInput.value = match.name;
                }
                setAccountStatus(index, 'existing', 'Existing account found for ' + match.name + ' — will be reused automatically.');
            } else {
                setAccountStatus(index, 'new', 'New supervisor account will be created for this email.');
            }
        }
    };
    xhr.send();
}

function checkDuplicates() {
    const emails = [];
    let hasDuplicate = false;
    for (let i = 0; i < maxCount; i++) {
        const emailInput = document.getElementById('sup_email_' + i);
        if (emailInput) {
            const val = emailInput.value.trim().toLowerCase();
            if (val) {
                if (emails.includes(val)) { hasDuplicate = true; break; }
                emails.push(val);
            }
        }
    }
    const warning = document.getElementById('duplicateWarning');
    const submitBtn = document.getElementById('submitBtn');
    warning.style.display = hasDuplicate ? 'block' : 'none';
    submitBtn.disabled = hasDuplicate;
}

// Wire up all inputs
document.addEventListener('DOMContentLoaded', function () {
    for (let i = 0; i < maxCount; i++) {
        const nameInput = document.getElementById('sup_name_' + i);
        const emailInput = document.getElementById('sup_email_' + i);

        if (nameInput) {
            nameInput.addEventListener('input', function () {
                debounce(function () {
                    searchSupervisors(nameInput.value.trim(), i, 'name');
                }, 300, 'name_' + i);
            });
            nameInput.addEventListener('blur', function () {
                setTimeout(function () { closeSuggestions(i); }, 200);
            });
        }

        if (emailInput) {
            emailInput.addEventListener('input', function () {
                checkDuplicates();
                debounce(function () {
                    checkEmailExists(emailInput.value.trim(), i);
                }, 400, 'email_' + i);
            });
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        for (let i = 0; i < maxCount; i++) {
            const dd = document.getElementById('name_suggestions_' + i);
            const nameInput = document.getElementById('sup_name_' + i);
            if (dd && nameInput && !dd.contains(e.target) && e.target !== nameInput) {
                dd.style.display = 'none';
            }
        }
    });
});
</script>
</x-app-layout>


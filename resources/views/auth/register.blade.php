<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="h4">ACETEL Progress Report Registration</h2>
        <p class="text-muted">Fill in your details to register for the presentation exercise.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="needs-validation">
        @csrf

        <h5 class="mb-3 text-primary border-bottom pb-2">Personal Information</h5>
        <div class="row g-3 mb-4">
            <!-- Name -->
            <div class="col-md-6">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Matric Number -->
            <div class="col-md-6">
                <label for="matric_number" class="form-label">Matric Number</label>
                <input type="text" class="form-control @error('matric_number') is-invalid @enderror" id="matric_number" name="matric_number" value="{{ old('matric_number') }}" required>
                @error('matric_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="col-md-6">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h5 class="mb-3 text-primary border-bottom pb-2">Academic Information</h5>
        <div class="row g-3 mb-4">
            <!-- Programme -->
            <div class="col-md-6">
                <label for="programme_id" class="form-label">Programme</label>
                <select class="form-select @error('programme_id') is-invalid @enderror" id="programme_id" name="programme_id" required>
                    <option value="" disabled selected>Select Programme...</option>
                    @foreach($programmes as $programme)
                        <option value="{{ $programme->id }}" {{ old('programme_id') == $programme->id ? 'selected' : '' }}>{{ $programme->name }}</option>
                    @endforeach
                </select>
                @error('programme_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Department -->
            <div class="col-md-6">
                <label for="department_id" class="form-label">Department</label>
                <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                    <option value="" disabled selected>Select Department...</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Supervisor Name -->
            <div class="col-md-6">
                <label for="supervisor_name" class="form-label">Supervisor Name</label>
                <input type="text" class="form-control @error('supervisor_name') is-invalid @enderror" id="supervisor_name" name="supervisor_name" value="{{ old('supervisor_name') }}" required>
                @error('supervisor_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Current Research Stage -->
            <div class="col-md-6">
                <label for="current_research_stage" class="form-label">Current Research Stage</label>
                <select class="form-select @error('current_research_stage') is-invalid @enderror" id="current_research_stage" name="current_research_stage" required>
                    <option value="" disabled selected>Select Stage...</option>
                    <option value="Proposal" {{ old('current_research_stage') == 'Proposal' ? 'selected' : '' }}>Proposal</option>
                    <option value="Data Collection" {{ old('current_research_stage') == 'Data Collection' ? 'selected' : '' }}>Data Collection</option>
                    <option value="Data Analysis" {{ old('current_research_stage') == 'Data Analysis' ? 'selected' : '' }}>Data Analysis</option>
                    <option value="Writing" {{ old('current_research_stage') == 'Writing' ? 'selected' : '' }}>Writing</option>
                    <option value="Final Review" {{ old('current_research_stage') == 'Final Review' ? 'selected' : '' }}>Final Review</option>
                </select>
                @error('current_research_stage')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Research Title -->
            <div class="col-md-12">
                <label for="research_title" class="form-label">Research Title</label>
                <textarea class="form-control @error('research_title') is-invalid @enderror" id="research_title" name="research_title" rows="2" required>{{ old('research_title') }}</textarea>
                @error('research_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Presentation Title -->
            <div class="col-md-12">
                <label for="presentation_title" class="form-label">Presentation Title</label>
                <textarea class="form-control @error('presentation_title') is-invalid @enderror" id="presentation_title" name="presentation_title" rows="2" required>{{ old('presentation_title') }}</textarea>
                @error('presentation_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h5 class="mb-3 text-primary border-bottom pb-2">Account Security</h5>
        <div class="row g-3 mb-4">
            <!-- Password -->
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>
        </div>

        <!-- Confirmation Checkbox -->
        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input @error('confirm_info') is-invalid @enderror" id="confirm_info" name="confirm_info" required>
            <label class="form-check-label text-muted" for="confirm_info">
                I confirm that all the information provided above is correct and accurate to the best of my knowledge.
            </label>
            @error('confirm_info')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a class="text-decoration-none" href="{{ route('login') }}">
                Already registered? Login here
            </a>
            <button type="submit" class="btn btn-primary px-4">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>

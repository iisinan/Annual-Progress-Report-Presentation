<x-app-layout>
    <x-slot name="header">
        System Settings
    </x-slot>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Configure System Deadlines & Parameters</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        
                        <h5 class="border-bottom pb-2 mb-3 text-secondary">Registration Deadlines</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Registration Open Date</label>
                                <input type="date" class="form-control" name="registration_open_date" value="{{ $settings['registration_open_date'] ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Registration Close Date</label>
                                <input type="date" class="form-control" name="registration_close_date" value="{{ $settings['registration_close_date'] ?? '' }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-danger fw-bold">Master Toggle: Student Registration</label>
                                <select class="form-select border-danger" name="is_registration_active">
                                    <option value="auto" {{ ($settings['is_registration_active'] ?? 'auto') == 'auto' ? 'selected' : '' }}>Automatic (Follow Dates)</option>
                                    <option value="1" {{ ($settings['is_registration_active'] ?? 'auto') == '1' ? 'selected' : '' }}>Force Active (Always Open)</option>
                                    <option value="0" {{ ($settings['is_registration_active'] ?? 'auto') == '0' ? 'selected' : '' }}>Force Deactivated (Always Closed)</option>
                                </select>
                                <small class="text-muted">Choose 'Automatic' to use the dates above. 'Force Active' or 'Force Deactivated' will override the dates.</small>
                            </div>
                        </div>

                        <h5 class="border-bottom pb-2 mb-3 text-secondary">Presentation Upload Deadlines</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Upload Open Date</label>
                                <input type="date" class="form-control" name="upload_open_date" value="{{ $settings['upload_open_date'] ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Upload Close Date</label>
                                <input type="date" class="form-control" name="upload_close_date" value="{{ $settings['upload_close_date'] ?? '' }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-danger fw-bold">Master Toggle: PDF Uploads</label>
                                <select class="form-select border-danger" name="is_upload_active">
                                    <option value="auto" {{ ($settings['is_upload_active'] ?? 'auto') == 'auto' ? 'selected' : '' }}>Automatic (Follow Dates)</option>
                                    <option value="1" {{ ($settings['is_upload_active'] ?? 'auto') == '1' ? 'selected' : '' }}>Force Active (Always Open)</option>
                                    <option value="0" {{ ($settings['is_upload_active'] ?? 'auto') == '0' ? 'selected' : '' }}>Force Deactivated (Always Closed)</option>
                                </select>
                                <small class="text-muted">Choose 'Automatic' to use the dates above. 'Force Active' or 'Force Deactivated' will override the dates.</small>
                            </div>
                        </div>

                        <h5 class="border-bottom pb-2 mb-3 text-secondary">Scheduling Engine Parameters</h5>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Presentation Start Date</label>
                                <input type="date" class="form-control" name="presentation_start_date" value="{{ $settings['presentation_start_date'] ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Students Per Day</label>
                                <input type="number" class="form-control" name="students_per_day" value="{{ $settings['students_per_day'] ?? '5' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Daily Start Time</label>
                                <input type="time" class="form-control" name="presentation_start_time" value="{{ $settings['presentation_start_time'] ?? '09:00' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control" name="presentation_duration" value="{{ $settings['presentation_duration'] ?? '30' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Break (minutes)</label>
                                <input type="number" class="form-control" name="break_duration" value="{{ $settings['break_duration'] ?? '0' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Venue</label>
                                <input type="text" class="form-control" name="venue" value="{{ $settings['venue'] ?? 'ACETEL Main Hall' }}">
                            </div>
                        </div>

                        <h5 class="border-bottom pb-2 mb-3 text-secondary">General Settings</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Institution Name</label>
                                <input type="text" class="form-control" name="institution_name" value="{{ $settings['institution_name'] ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Academic Session</label>
                                <input type="text" class="form-control" name="academic_session" value="{{ $settings['academic_session'] ?? '' }}">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-2"></i> Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

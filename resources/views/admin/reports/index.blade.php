<x-app-layout>
    <x-slot name="header">
        Reports & Exports
    </x-slot>

    <div class="row g-4">
        <!-- Registered Students Report -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-5">
                    <i class="fa-solid fa-users fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Registered Students</h5>
                    <p class="text-muted mb-4">Export the complete list of registered students, including their research details and presentation upload status.</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.reports.students.excel') }}" class="btn btn-success"><i class="fa-solid fa-file-excel me-2"></i> Export Excel</a>
                        <a href="{{ route('admin.reports.students.pdf') }}" class="btn btn-danger"><i class="fa-solid fa-file-pdf me-2"></i> Export PDF</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Presentation Schedule Report -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-5">
                    <i class="fa-solid fa-calendar-days fa-3x text-info mb-3"></i>
                    <h5 class="fw-bold">Presentation Schedule</h5>
                    <p class="text-muted mb-4">Export the generated schedule, grouped by date. Useful for sharing with examiners and posting on notice boards.</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.reports.schedule.pdf') }}" class="btn btn-danger"><i class="fa-solid fa-file-pdf me-2"></i> Export PDF</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Sheet Report (Placeholder for future) -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-5 opacity-75">
                    <i class="fa-solid fa-clipboard-user fa-3x text-secondary mb-3"></i>
                    <h5 class="fw-bold">Attendance Sheets</h5>
                    <p class="text-muted mb-4">Generate blank attendance sheets based on daily schedules for students to sign during their presentations.</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <button disabled class="btn btn-secondary"><i class="fa-solid fa-file-pdf me-2"></i> Coming Soon</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Master Grades Report -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body text-center p-5">
                    <i class="fa-solid fa-graduation-cap fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Master Grades Ledger</h5>
                    <p class="text-muted mb-4">Export the final graded results of all presented students, including individual category scores and examiner remarks.</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.reports.grades.excel') }}" class="btn btn-success"><i class="fa-solid fa-file-excel me-2"></i> Export Grades (Excel)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">Upload Presentation (PDF)</x-slot>

    <div class="page-title-bar mb-4">
        <div>
            <h1><i class="fa-solid fa-file-pdf me-2"></i> Upload Presentation</h1>
            <small style="color:rgba(255,255,255,0.75);">Submit your PDF presentation before the deadline</small>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header py-3" style="background:var(--acetel-green-pale);border-bottom:2px solid var(--acetel-green);">
                    <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload PDF Presentation</h6>
                </div>
                <div class="card-body">
                    
                    @if($presentation && $presentation->file_path)
                        <div class="text-center py-4">
                            <div class="mb-3" style="width:72px;height:72px;background:var(--acetel-green-pale);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                                <i class="fa-solid fa-circle-check fa-2x" style="color:var(--acetel-green);"></i>
                            </div>
                            <h5 class="fw-bold" style="color:var(--acetel-green);">Upload Complete!</h5>
                            <p class="text-muted mb-1">Your presentation has been successfully uploaded:</p>
                            <p class="fw-bold mb-1">{{ $presentation->original_filename }}</p>
                            <small class="text-muted">Uploaded on {{ $presentation->uploaded_at->format('d M Y, h:i A') }}</small>
                        </div>
                        <div class="text-center pb-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-acetel px-4">
                                <i class="fa-solid fa-gauge me-2"></i>Return to Dashboard
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('student.upload.store') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Info notice --}}
                            <div class="p-3 mb-4 rounded-3 d-flex gap-3 align-items-start" style="background:var(--acetel-green-pale);border:1px solid rgba(26,122,50,0.2);">
                                <i class="fa-solid fa-circle-info mt-1" style="color:var(--acetel-green);"></i>
                                <div style="font-size:0.88rem;">
                                    <strong style="color:var(--acetel-green);">PDF format required.</strong>
                                    Please export your PowerPoint slides as a PDF before uploading.
                                    Maximum file size: <strong>100MB</strong>. You can only upload <strong>once</strong>.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="presentation_file" class="form-label fw-semibold">Select PDF File</label>
                                <input class="form-control @error('presentation_file') is-invalid @enderror"
                                       type="file" id="presentation_file" name="presentation_file"
                                       accept=".pdf,application/pdf" required>
                                <div class="form-text"><i class="fa-solid fa-lock me-1"></i>Only <strong>.pdf</strong> files are accepted. Max size: 100MB.</div>
                                @error('presentation_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4" id="progressWrapper" style="display: none;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-bold" style="color:var(--acetel-green);">Uploading...</span>
                                    <span class="small fw-bold" id="progressText" style="color:var(--acetel-green);">0%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="uploadProgressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <button type="button" id="uploadButton" class="btn btn-acetel w-100 py-2">
                                <i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload PDF Presentation
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadBtn = document.getElementById('uploadButton');
            const fileInput = document.getElementById('presentation_file');
            const progressWrapper = document.getElementById('progressWrapper');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressText = document.getElementById('progressText');
            const form = document.querySelector('form');

            if(uploadBtn) {
                uploadBtn.addEventListener('click', function(e) {
                    if (!fileInput.files.length) {
                        alert('Please select a PDF file first.');
                        return;
                    }
                    
                    e.preventDefault();
                    
                    uploadBtn.disabled = true;
                    uploadBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Uploading... Please wait';
                    progressWrapper.style.display = 'block';

                    const formData = new FormData(form);
                    const xhr = new XMLHttpRequest();
                    
                    xhr.open('POST', form.action, true);
                    
                    xhr.upload.onprogress = function(e) {
                        if (e.lengthComputable) {
                            const percentComplete = Math.round((e.loaded / e.total) * 100);
                            progressBar.style.width = percentComplete + '%';
                            progressBar.setAttribute('aria-valuenow', percentComplete);
                            progressText.innerText = percentComplete + '%';
                        }
                    };

                    xhr.onload = function() {
                        if (xhr.status >= 200 && xhr.status < 400) {
                            progressBar.style.width = '100%';
                            progressText.innerText = '100%';
                            progressText.innerText = 'Upload Complete! Reloading...';
                            
                            // It returns a redirect normally, or success response
                            window.location.reload();
                        } else {
                            alert('An error occurred during the upload. Please try again.');
                            uploadBtn.disabled = false;
                            uploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload PDF Presentation';
                            progressWrapper.style.display = 'none';
                            
                            // Check if validation error
                            if(xhr.status === 422) {
                                try {
                                    let res = JSON.parse(xhr.responseText);
                                    if(res.errors && res.errors.presentation_file) {
                                        alert(res.errors.presentation_file[0]);
                                    }
                                } catch(e){}
                            }
                        }
                    };

                    xhr.onerror = function() {
                        alert('Network Error. Please check your connection and try again.');
                        uploadBtn.disabled = false;
                        uploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload PDF Presentation';
                        progressWrapper.style.display = 'none';
                    };

                    // Send the form data
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.send(formData);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>

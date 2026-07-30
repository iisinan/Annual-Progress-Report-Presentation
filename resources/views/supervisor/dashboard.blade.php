<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">My Students</h3>

                    @if($students->isEmpty())
                        <p class="text-gray-500">You have no students assigned yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programme</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Research Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presentation</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ optional($student->user)->name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $student->matric_number }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ optional($student->programme)->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $student->research_title }}">
                                                {{ $student->research_title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($student->presentation && $student->presentation->file_path)
                                                    <a href="{{ Storage::url($student->presentation->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900 underline">View Presentation</a>
                                                @else
                                                    <span class="text-gray-400">Not Uploaded</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $status = $student->pivot->status;
                                                    $statusClass = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'approved' => 'bg-green-100 text-green-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                    ][$status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="openReviewModal('{{ $student->id }}', '{{ $student->user->name }}')" class="text-indigo-600 hover:text-indigo-900">Review</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeReviewModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modal-title">
                        Review Presentation for <span id="modal-student-name"></span>
                    </h3>
                    
                    <form id="approveForm" method="POST" action="">
                        @csrf
                        <div class="mb-4">
                            <label for="comments" class="block text-sm font-medium text-gray-700">Comments (Required for rejection)</label>
                            <textarea id="comments" name="comments" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                        </div>
                        
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" onclick="closeReviewModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button type="button" onclick="submitReview('reject')" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Reject
                            </button>
                            <button type="button" onclick="submitReview('approve')" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Approve
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openReviewModal(studentId, studentName) {
            document.getElementById('modal-student-name').textContent = studentName;
            document.getElementById('reviewModal').classList.remove('hidden');
            
            // Store student ID globally for the submit function
            window.currentReviewStudentId = studentId;
            document.getElementById('comments').value = '';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }

        function submitReview(action) {
            const form = document.getElementById('approveForm');
            const studentId = window.currentReviewStudentId;
            
            if (action === 'approve') {
                form.action = `/supervisor/approve/${studentId}`;
            } else if (action === 'reject') {
                form.action = `/supervisor/reject/${studentId}`;
                const comments = document.getElementById('comments').value;
                if (!comments.trim()) {
                    alert('Comments are required for rejection.');
                    return;
                }
            }
            
            form.submit();
        }
    </script>
</x-app-layout>

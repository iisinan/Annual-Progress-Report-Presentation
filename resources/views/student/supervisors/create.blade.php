<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Assign Your Supervisors') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Instructions Banner -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Important Instructions</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>
                                Based on your programme, you are required to assign exactly <strong>{{ $requiredCount }} supervisors</strong>. 
                                Please provide their correct full names and institutional email addresses. 
                                The system will automatically create accounts for them and send them an email invitation to review your presentation.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <form method="POST" action="{{ route('student.supervisors.store') }}" id="supervisor-form">
                        @csrf

                        <div class="space-y-8">
                            @for ($i = 0; $i < $requiredCount; $i++)
                                <div class="relative bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    
                                    <!-- Badge -->
                                    <div class="absolute -top-3 left-4 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                        Supervisor {{ $i + 1 }}
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                                        <!-- Name Field -->
                                        <div>
                                            <x-input-label for="supervisors_{{ $i }}_name" :value="__('Full Name with Title')" class="text-gray-700 font-medium" />
                                            <div class="relative mt-1">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <input id="supervisors_{{ $i }}_name" type="text" name="supervisors[{{ $i }}][name]" value="{{ old('supervisors.'.$i.'.name') }}" class="pl-10 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150 ease-in-out" placeholder="e.g. Dr. John Doe" required autofocus="{{ $i === 0 ? 'true' : 'false' }}">
                                            </div>
                                            <x-input-error :messages="$errors->get('supervisors.'.$i.'.name')" class="mt-2" />
                                        </div>

                                        <!-- Email Field -->
                                        <div>
                                            <x-input-label for="supervisors_{{ $i }}_email" :value="__('Email Address')" class="text-gray-700 font-medium" />
                                            <div class="relative mt-1">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <input id="supervisors_{{ $i }}_email" type="email" name="supervisors[{{ $i }}][email]" value="{{ old('supervisors.'.$i.'.email') }}" class="pl-10 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150 ease-in-out" placeholder="e.g. supervisor@noun.edu.ng" required>
                                            </div>
                                            <x-input-error :messages="$errors->get('supervisors.'.$i.'.email')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t border-gray-100 pt-6">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Confirm & Assign Supervisors') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

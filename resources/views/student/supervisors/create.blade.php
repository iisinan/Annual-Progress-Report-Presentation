<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Supervisors') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('Please provide the details of your :count supervisors. An account will be automatically created for them.', ['count' => $requiredCount]) }}
                    </div>

                    <form method="POST" action="{{ route('student.supervisors.store') }}">
                        @csrf

                        @for ($i = 0; $i < $requiredCount; $i++)
                            <div class="mb-6 p-4 border rounded-lg bg-gray-50">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Supervisor {{ $i + 1 }}</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="supervisors_{{ $i }}_name" :value="__('Name')" />
                                        <x-text-input id="supervisors_{{ $i }}_name" class="block mt-1 w-full" type="text" name="supervisors[{{ $i }}][name]" :value="old('supervisors.'.$i.'.name')" required autofocus />
                                        <x-input-error :messages="$errors->get('supervisors.'.$i.'.name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="supervisors_{{ $i }}_email" :value="__('Email')" />
                                        <x-text-input id="supervisors_{{ $i }}_email" class="block mt-1 w-full" type="email" name="supervisors[{{ $i }}][email]" :value="old('supervisors.'.$i.'.email')" required />
                                        <x-input-error :messages="$errors->get('supervisors.'.$i.'.email')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        @endfor

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Assign Supervisors') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-900">Create New Court Appointment</h2>
                    </div>

                    <form method="POST" action="{{ route('appointments.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <!-- Case Number -->
                            <div>
                                <x-input-label for="case_number" :value="__('Case Number')" />
                                <x-text-input id="case_number" name="case_number" type="text" class="mt-1 block w-full" :value="old('case_number')" />
                                <x-input-error class="mt-2" :messages="$errors->get('case_number')" />
                            </div>

                            <!-- Start Time -->
                            <div>
                                <x-input-label for="start_time" :value="__('Start Time')" />
                                <x-text-input id="start_time" name="start_time" type="datetime-local" class="mt-1 block w-full" :value="old('start_time')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                            </div>

                            <!-- End Time -->
                            <div>
                                <x-input-label for="end_time" :value="__('End Time')" />
                                <x-text-input id="end_time" name="end_time" type="datetime-local" class="mt-1 block w-full" :value="old('end_time')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                            </div>

                            <!-- Court Location -->
                            <div>
                                <x-input-label for="court_location" :value="__('Court Location')" />
                                <x-text-input id="court_location" name="court_location" type="text" class="mt-1 block w-full" :value="old('court_location')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('court_location')" />
                            </div>

                            <!-- Case Type -->
                            <div>
                                <x-input-label for="case_type" :value="__('Case Type')" />
                                <x-text-input id="case_type" name="case_type" type="text" class="mt-1 block w-full" :value="old('case_type')" />
                                <x-input-error class="mt-2" :messages="$errors->get('case_type')" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-[#3F51B5] focus:ring-[#3F51B5] rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Assigned Lawyers -->
                        <div>
                            <x-input-label :value="__('Assigned Lawyers')" />
                            <div class="mt-2 space-y-2">
                                @foreach($lawyers as $lawyer)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="lawyers[]" value="{{ $lawyer->id }}" class="rounded border-gray-300 text-[#3F51B5] shadow-sm focus:ring-[#3F51B5]">
                                        <span class="ml-2">{{ $lawyer->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('lawyers')" />
                        </div>

                        <!-- Reminder Settings -->
                        <div>
                            <x-input-label :value="__('Reminder Settings')" />
                            <div class="mt-2 space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="reminders[]" value="1_day" class="rounded border-gray-300 text-[#3F51B5] shadow-sm focus:ring-[#3F51B5]">
                                    <span class="ml-2">1 day before</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="reminders[]" value="1_hour" class="rounded border-gray-300 text-[#3F51B5] shadow-sm focus:ring-[#3F51B5]">
                                    <span class="ml-2">1 hour before</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="reminders[]" value="same_day" class="rounded border-gray-300 text-[#3F51B5] shadow-sm focus:ring-[#3F51B5]">
                                    <span class="ml-2">On the day</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Create Appointment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

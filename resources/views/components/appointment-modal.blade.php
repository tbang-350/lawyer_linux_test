@props(['appointment'])

<div x-data="{ show: false }"
     x-show="show"
     x-on:open-modal.window="if ($event.detail.id === {{ $appointment->id }}) show = true"
     x-on:close-modal.window="show = false"
     x-on:keydown.escape.window="show = false"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">

    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <!-- Modal panel -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

            <!-- Close button -->
            <div class="absolute right-0 top-0 pr-4 pt-4">
                <button type="button" @click="show = false" class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal content -->
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                        {{ $appointment->title }}
                    </h3>

                    <div class="mt-4 space-y-4">
                        <!-- Date and Time -->
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $appointment->start_time->format('F j, Y g:i A') }} - {{ $appointment->end_time->format('g:i A') }}
                        </div>

                        <!-- Court Location -->
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $appointment->court_location }}
                        </div>

                        <!-- Case Details -->
                        @if($appointment->case_number)
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Case #{{ $appointment->case_number }}
                        </div>
                        @endif

                        <!-- Assigned Lawyers -->
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Assigned Lawyers</h4>
                            <div class="mt-2 space-y-2">
                                @foreach($appointment->lawyers as $lawyer)
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $lawyer->name }}
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Description</h4>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $appointment->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-6 flex justify-end space-x-3">
                        @if(Auth::user()->isAdmin() || $appointment->lawyers->contains(Auth::id()))
                        <a href="{{ route('appointments.edit', $appointment) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Edit
                        </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to delete this appointment?')">
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

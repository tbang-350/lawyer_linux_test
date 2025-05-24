<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Calendar') }}
            </h2>
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-[#3F51B5] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#303F9F] focus:bg-[#303F9F] active:bg-[#283593] focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:ring-offset-2 transition ease-in-out duration-150">
                New Appointment
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Modals -->
    @foreach($appointments as $appointment)
        <x-appointment-modal :appointment="$appointment" />
    @endforeach

    @push('styles')
    <style>
        .calendar-container {
            height: 800px;
        }
        .fc-event {
            cursor: pointer;
        }
        .fc-event-title {
            font-weight: 500;
        }
        .fc-event-time {
            font-size: 0.85em;
        }
        .fc-toolbar-title {
            font-size: 1.5em !important;
            font-weight: 600 !important;
        }
        .fc-button-primary {
            background-color: #3F51B5 !important;
            border-color: #3F51B5 !important;
        }
        .fc-button-primary:hover {
            background-color: #303F9F !important;
            border-color: #303F9F !important;
        }
        .fc-button-primary:disabled {
            background-color: #9FA8DA !important;
            border-color: #9FA8DA !important;
        }
        .fc-daygrid-event {
            background-color: #3F51B5;
            border-color: #3F51B5;
        }
        .fc-daygrid-event:hover {
            background-color: #303F9F;
            border-color: #303F9F;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json($appointments->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'start' => $appointment->start_time,
                    'end' => $appointment->end_time,
                    'extendedProps' => [
                        'court_location' => $appointment->court_location,
                        'case_number' => $appointment->case_number,
                        'description' => $appointment->description
                    ]
                ];
            })),
            eventClick: function(info) {
                // Dispatch event to open modal
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: { id: info.event.id }
                }));
            },
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            height: 'auto',
            themeSystem: 'standard',
            eventColor: '#3F51B5',
            eventTextColor: '#ffffff',
            eventBorderColor: '#303F9F',
            eventBackgroundColor: '#3F51B5',
            eventDisplay: 'block',
            eventDidMount: function(info) {
                // Add tooltip
                info.el.title = info.event.title;
            }
        });
        calendar.render();
    });
    </script>
    @endpush
</x-app-layout>

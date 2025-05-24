import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: '/api/appointments',
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        select: function(info) {
            // Handle date selection for new appointment
            window.location.href = `/appointments/create?start=${info.startStr}&end=${info.endStr}`;
        },
        eventClick: function(info) {
            // Handle event click to show appointment details
            window.location.href = `/appointments/${info.event.id}`;
        },
        eventDrop: function(info) {
            // Handle event drag and drop
            fetch(`/api/appointments/${info.event.id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    start_time: info.event.start,
                    end_time: info.event.end
                })
            });
        },
        eventResize: function(info) {
            // Handle event resize
            fetch(`/api/appointments/${info.event.id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    start_time: info.event.start,
                    end_time: info.event.end
                })
            });
        }
    });

    calendar.render();
});

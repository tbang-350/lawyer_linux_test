<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminder;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send reminders for upcoming court appointments';

    public function handle()
    {
        $now = now();

        // Get appointments that need reminders
        $appointments = Appointment::where('status', 'scheduled')
            ->where('start_time', '>', $now)
            ->where('start_time', '<=', $now->addDay())
            ->whereNotNull('reminder_settings')
            ->get();

        foreach ($appointments as $appointment) {
            $reminderSettings = $appointment->reminder_settings;

            foreach ($reminderSettings as $setting) {
                $shouldSend = false;

                switch ($setting) {
                    case '1_day':
                        $shouldSend = $appointment->start_time->subDay()->isSameDay($now);
                        break;
                    case '1_hour':
                        $shouldSend = $appointment->start_time->subHour()->isSameHour($now);
                        break;
                    case 'same_day':
                        $shouldSend = $appointment->start_time->isSameDay($now);
                        break;
                }

                if ($shouldSend) {
                    foreach ($appointment->lawyers as $lawyer) {
                        Mail::to($lawyer->email)
                            ->queue(new AppointmentReminder($appointment));

                        $this->info("Sent reminder for appointment {$appointment->title} to {$lawyer->name}");
                    }
                }
            }
        }

        $this->info('Appointment reminders sent successfully.');
    }
}

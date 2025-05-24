<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminder;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['lawyers', 'creator'])
            ->when(!Auth::user()->isAdmin(), function ($query) {
                return $query->whereHas('lawyers', function ($q) {
                    $q->where('users.id', Auth::id());
                });
            })
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'start' => $appointment->start_time,
                    'end' => $appointment->end_time,
                    'description' => $appointment->description,
                    'court_location' => $appointment->court_location,
                    'case_number' => $appointment->case_number,
                    'case_type' => $appointment->case_type,
                    'status' => $appointment->status,
                    'lawyers' => $appointment->lawyers->pluck('name'),
                ];
            });

        $upcomingAppointmentsCount = Appointment::upcoming()->count();
        $pastAppointmentsCount = Appointment::past()->count();
        $activeLawyersCount = User::where('role', 'lawyer')->count();
        $scheduledRemindersCount = Appointment::whereNotNull('reminder_settings')->count();

        return view('dashboard', compact(
            'appointments',
            'upcomingAppointmentsCount',
            'pastAppointmentsCount',
            'activeLawyersCount',
            'scheduledRemindersCount'
        ));
    }

    public function create()
    {
        $lawyers = User::where('role', 'lawyer')->get();
        return view('appointments.create', compact('lawyers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'court_location' => 'required|string|max:255',
            'case_number' => 'nullable|string|max:255',
            'case_type' => 'nullable|string|max:255',
            'lawyers' => 'required|array|min:1',
            'lawyers.*' => 'exists:users,id',
            'reminders' => 'nullable|array',
        ]);

        $appointment = Appointment::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'court_location' => $validated['court_location'],
            'case_number' => $validated['case_number'],
            'case_type' => $validated['case_type'],
            'status' => 'scheduled',
            'reminder_settings' => $validated['reminders'] ?? [],
            'created_by' => Auth::id(),
        ]);

        $appointment->lawyers()->attach($validated['lawyers']);

        // Schedule reminders
        if (!empty($validated['reminders'])) {
            foreach ($validated['reminders'] as $reminder) {
                $this->scheduleReminder($appointment, $reminder);
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $lawyers = User::where('role', 'lawyer')->get();
        return view('appointments.edit', compact('appointment', 'lawyers'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'court_location' => 'required|string|max:255',
            'case_number' => 'nullable|string|max:255',
            'case_type' => 'nullable|string|max:255',
            'lawyers' => 'required|array|min:1',
            'lawyers.*' => 'exists:users,id',
            'reminders' => 'nullable|array',
        ]);

        $appointment->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'court_location' => $validated['court_location'],
            'case_number' => $validated['case_number'],
            'case_type' => $validated['case_type'],
            'reminder_settings' => $validated['reminders'] ?? [],
        ]);

        $appointment->lawyers()->sync($validated['lawyers']);

        // Update reminders
        if (!empty($validated['reminders'])) {
            foreach ($validated['reminders'] as $reminder) {
                $this->scheduleReminder($appointment, $reminder);
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Appointment deleted successfully.');
    }

    protected function scheduleReminder(Appointment $appointment, string $reminder)
    {
        $reminderTime = null;
        switch ($reminder) {
            case '1_day':
                $reminderTime = $appointment->start_time->subDay();
                break;
            case '1_hour':
                $reminderTime = $appointment->start_time->subHour();
                break;
            case 'same_day':
                $reminderTime = $appointment->start_time->startOfDay();
                break;
        }

        if ($reminderTime) {
            foreach ($appointment->lawyers as $lawyer) {
                Mail::to($lawyer->email)->later($reminderTime, new AppointmentReminder($appointment));
            }
        }
    }
}

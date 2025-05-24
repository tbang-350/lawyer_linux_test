<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                    'color' => $this->getEventColor($appointment->status),
                ];
            });

        return response()->json($appointments);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $appointment->update($validated);

        return response()->json($appointment);
    }

    protected function getEventColor($status)
    {
        return match($status) {
            'scheduled' => '#3F51B5',
            'completed' => '#4CAF50',
            'cancelled' => '#F44336',
            default => '#3F51B5',
        };
    }
}

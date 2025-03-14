<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\NotificationType;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppointmentApiController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = Appointment::with(['client', 'notificationType']);

        // Филтриране по дата
        if ($request->has('date_from')) {
            $query->where('appointment_datetime', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('appointment_datetime', '<=', $request->date_to);
        }

        // Филтриране по ЕГН
        if ($request->has('egn')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('egn', $request->egn);
            });
        }

        $appointments = $query->orderBy('appointment_datetime')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'egn' => 'required|string|size:10',
            'appointment_datetime' => 'required|date|after:now',
            'description' => 'nullable|string',
            'notification_type_id' => 'required|exists:notification_types,id',
            'email' => Rule::requiredIf(function () use ($request) {
                return $request->notification_type_id == 1; // Email notification
            }),
            'phone' => Rule::requiredIf(function () use ($request) {
                return $request->notification_type_id == 2; // SMS notification
            }),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Find or create client
        $client = Client::firstOrCreate(
            ['egn' => $request->egn],
            [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]
        );

        // Create appointment
        $appointment = Appointment::create([
            'client_id' => $client->id,
            'notification_type_id' => $request->notification_type_id,
            'appointment_datetime' => $request->appointment_datetime,
            'description' => $request->description,
        ]);

        // Send notification
        $notificationMessage = $this->notificationService->sendNotification($appointment);

        return response()->json([
            'success' => true,
            'message' => "Успешно запазихте час! {$notificationMessage}",
            'data' => $appointment->load('client', 'notificationType')
        ], 201);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['client', 'notificationType'])->findOrFail($id);
        
        // Get other upcoming appointments for this client
        $otherAppointments = $appointment->client
            ->futureAppointments()
            ->where('id', '!=', $appointment->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'appointment' => $appointment,
                'other_appointments' => $otherAppointments
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'egn' => 'required|string|size:10',
            'appointment_datetime' => 'required|date|after:now',
            'description' => 'nullable|string',
            'notification_type_id' => 'required|exists:notification_types,id',
            'email' => Rule::requiredIf(function () use ($request) {
                return $request->notification_type_id == 1; // Email notification
            }),
            'phone' => Rule::requiredIf(function () use ($request) {
                return $request->notification_type_id == 2; // SMS notification
            }),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Update client
        $client = $appointment->client;
        $client->update([
            'name' => $request->name,
            'egn' => $request->egn,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update appointment
        $appointment->update([
            'notification_type_id' => $request->notification_type_id,
            'appointment_datetime' => $request->appointment_datetime,
            'description' => $request->description,
        ]);

        // Send notification
        $notificationMessage = $this->notificationService->sendNotification($appointment);

        return response()->json([
            'success' => true,
            'message' => "Успешно редактирахте час! {$notificationMessage}",
            'data' => $appointment->load('client', 'notificationType')
        ]);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Часът беше успешно изтрит!'
        ]);
    }
}

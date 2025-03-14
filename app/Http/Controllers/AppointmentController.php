<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\NotificationType;
use App\Services\ClientService;
use App\Services\NotificationService;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected NotificationService $notificationService;
    protected ClientService $clientService;

    public function __construct(NotificationService $notificationService, ClientService $clientService)
    {
        $this->notificationService = $notificationService;
        $this->clientService = $clientService;
    }

    public function index(Request $request)
    {
        $query = Appointment::with(['client', 'notificationType']);

        if ($request->filled('date_from')) {
            $query->where('appointment_datetime', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_datetime', '<=', $request->date_to);
        }

        if ($request->filled('egn')) {
            $query->whereHas('client', fn($q) => $q->where('egn', $request->egn));
        }

        $appointments = $query->orderBy('appointment_datetime')->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('appointments.create', [
            'notificationTypes' => NotificationType::all(),
        ]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $client = $this->clientService->findOrCreateClient($request->validated());

        $appointment = Appointment::create([
            'client_id' => $client->id,
            'notification_type_id' => $request->notification_type_id,
            'appointment_datetime' => $request->appointment_datetime,
            'description' => $request->description,
        ]);

        $notificationMessage = $this->notificationService->sendNotification($appointment);

        return redirect()->route('appointments.index')->with('success', "Успешно запазихте час! {$notificationMessage}");
    }

    public function show(Appointment $appointment)
    {
        return view('appointments.show', [
            'appointment' => $appointment->load('client', 'notificationType'),
            'otherAppointments' => $appointment->client->futureAppointments()->where('id', '!=', $appointment->id)->get(),
        ]);
    }

    public function edit(Appointment $appointment)
    {
        return view('appointments.edit', [
            'appointment' => $appointment->load('client'),
            'notificationTypes' => NotificationType::all(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->client->update($request->validated());

        $appointment->update([
            'notification_type_id' => $request->notification_type_id,
            'appointment_datetime' => $request->appointment_datetime,
            'description' => $request->description,
        ]);

        $notificationMessage = $this->notificationService->sendNotification($appointment);

        return redirect()->route('appointments.show', $appointment)->with('success', "Успешно редактирахте час! {$notificationMessage}");
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Часът беше успешно изтрит!');
    }
}

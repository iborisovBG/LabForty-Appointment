@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Детайли за час</h2>
            <div>
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning">Редактирай</a>
                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Сигурни ли сте?')">Изтрий</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Информация за часа</h4>
                    <table class="table">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $appointment->id }}</td>
                        </tr>
                        <tr>
                            <th>Дата и час:</th>
                            <td>{{ $appointment->appointment_datetime->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Тип нотификация:</th>
                            <td>{{ $appointment->notificationType->name }}</td>
                        </tr>
                        <tr>
                            <th>Описание:</th>
                            <td>{{ $appointment->description ?: 'Няма описание' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Информация за клиента</h4>
                    <table class="table">
                        <tr>
                            <th>Име:</th>
                            <td>{{ $appointment->client->name }}</td>
                        </tr>
                        <tr>
                            <th>ЕГН:</th>
                            <td>{{ $appointment->client->egn }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $appointment->client->email ?: 'Не е въведен' }}</td>
                        </tr>
                        <tr>
                            <th>Телефон:</th>
                            <td>{{ $appointment->client->phone ?: 'Не е въведен' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            @if($otherAppointments->count() > 0)
                <div class="mt-4">
                    <h4>Други предстоящи часове за този клиент</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Дата и час</th>
                                <th>Тип нотификация</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($otherAppointments as $otherAppointment)
                                <tr>
                                    <td>{{ $otherAppointment->id }}</td>
                                    <td>
                                       {{ $otherAppointment->appointment_datetime ? $otherAppointment->appointment_datetime->format('d.m.Y H:i') : 'Няма дата' }}
                                    </td>

                                    <td>
                                        {{ $otherAppointment->notificationType ? $otherAppointment->notificationType->name : 'Няма тип нотификация' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('appointments.show', $otherAppointment) }}" class="btn btn-sm btn-info">Детайли</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            
            <div class="mt-3">
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Обратно към списъка</a>
            </div>
        </div>
    </div>
@endsection
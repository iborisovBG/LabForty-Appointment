@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Списък с часове</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('appointments.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">От дата</label>
                            <input type="date" id="date_from" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">До дата</label>
                            <input type="date" id="date_to" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="egn">ЕГН</label>
                            <input type="text" id="egn" name="egn" class="form-control" value="{{ request('egn') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary form-control">Филтрирай</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата и час</th>
                            <th>Клиент</th>
                            <th>ЕГН</th>
                            <th>Тип нотификация</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->id }}</td>
                                <td>{{ $appointment->appointment_datetime->format('d.m.Y H:i') }}</td>
                                <td>{{ $appointment->client->name }}</td>
                                <td>{{ $appointment->client->egn }}</td>
                                <td>{{ $appointment->notificationType->name }}</td>
                                <td>
                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">Детайли</a>
                                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">Редактирай</a>
                                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Сигурни ли сте?')">Изтрий</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Няма намерени часове</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $appointments->links() }}
        </div>
    </div>
@endsection
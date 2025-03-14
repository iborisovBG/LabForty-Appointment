@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Редактиране на час</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Име на клиента</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $appointment->client->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="egn">ЕГН</label>
                            <input type="text" id="egn" name="egn" class="form-control @error('egn') is-invalid @enderror" value="{{ old('egn', $appointment->client->egn) }}" maxlength="10">
                            @error('egn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="appointment_datetime">Дата и час</label>
                            <input type="datetime-local" id="appointment_datetime" name="appointment_datetime" class="form-control @error('appointment_datetime') is-invalid @enderror" 
                                value="{{ old('appointment_datetime', $appointment->appointment_datetime->format('Y-m-d\TH:i')) }}">
                            @error('appointment_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="notification_type_id">Тип нотификация</label>
                            <select id="notification_type_id" name="notification_type_id" class="form-control @error('notification_type_id') is-invalid @enderror">
                                <option value="">Изберете тип</option>
                                @foreach($notificationTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('notification_type_id', $appointment->notification_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('notification_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $appointment->client->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Телефон</label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $appointment->client->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="description">Описание</label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $appointment->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Запази промените</button>
                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary">Отказ</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationTypeSelect = document.getElementById('notification_type_id');
            const emailField = document.getElementById('email');
            const phoneField = document.getElementById('phone');

            function toggleContactFields() {
                const selectedValue = notificationTypeSelect.value;
                
                if (selectedValue === '1') { // Email
                    emailField.parentElement.style.display = 'block';
                    phoneField.parentElement.style.display = 'none';
                } else if (selectedValue === '2') { // SMS
                    emailField.parentElement.style.display = 'none';
                    phoneField.parentElement.style.display = 'block';
                } else {
                    emailField.parentElement.style.display = 'block';
                    phoneField.parentElement.style.display = 'block';
                }
            }

            notificationTypeSelect.addEventListener('change', toggleContactFields);
            toggleContactFields();
        });
    </script>
@endsection
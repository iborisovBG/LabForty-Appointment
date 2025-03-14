# LabForty-Appointment
 
## Инсталиране и стартиране на проекта

### 1. Клониране на репозитория

Първо, клонирайте репото като използвате Git:

```bash
git clone https://github.com/iborisovBG/LabForty-Appointment
cd LabForty-Appointment

2. Инсталиране на зависимостите с Composer
След като вече сме клонирали репото трябва да инсталираме зависимостите в проекта, посочени в composer.json:
```bash
composer install

3. Конфигуриране на .env файл
Създайте копие на .env.example и го именувайте .env. След това, отворете .env файла и конфигурирайте връзката към вашата база данни:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=appointment_app
DB_USERNAME=root
DB_PASSWORD=вашата парола тук

4. Генериране на ключ за приложението
Laravel изисква генериране на уникален ключ за приложението. Това може да се направи чрез следната команда:
```bash
php artisan key:generate


5. Изпълнение на миграции
Изпълнете миграциите, за да създадете необходимите таблици в базата данни:
```bash
php artisan migrate


6. Seed на данните (по избор)
Ако искате да попълните базата данни със стартови данни (пример, за типове нотификации), използвайте командата:
```bash
php artisan db:seed


7. Стартиране на приложението
Стартирайте вградения сървър за разработка на Laravel:
```bash
php artisan serve


API Endpoints
1. GET /api/appointments
Получаване на списък с всички часове с възможност за филтриране по дата и ЕГН.

Пример:
```bash
curl -X GET http://localhost:8000/api/appointments?date_from=2025-03-01&date_to=2025-03-31&egn=8502146789


2. POST /api/appointments
Създаване на нов час.

Пример:
```bash
curl -X POST http://localhost:8000/api/appointments \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Иван Иванов",
    "egn": "8502146789",
    "appointment_datetime": "2025-03-20T10:00:00",
    "description": "Консултация",
    "notification_type_id": 1,
    "email": "ivan@example.com",
    "phone": "0888123456"
  }'


3. GET /api/appointments/{id}
Получаване на подробности за конкретен час по неговото ID.

Пример:

```bash
curl -X GET http://localhost:8000/api/appointments/1


4. PUT /api/appointments/{id}
Редактиране на съществуващ час по неговото ID.

Пример:
```bash
curl -X PUT http://localhost:8000/api/appointments/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Иван Петров",
    "egn": "8502146789",
    "appointment_datetime": "2025-03-21T11:00:00",
    "description": "Консултация и преглед",
    "notification_type_id": 2,
    "email": "ivan@example.com",
    "phone": "0888123456"
  }'


5. DELETE /api/appointments/{id}
Изтриване на час по неговото ID.

Пример:
```bash
curl -X DELETE http://localhost:8000/api/appointments/1


Изисквания
Laravel 8.x или по-нова версия
MySQL база данни
Composer
Основни познания в PHP, Laravel и RESTful API

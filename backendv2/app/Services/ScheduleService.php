<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Schedule;
use App\Repositories\ScheduleRepositories\ScheduleRepositoryInterface;
use DateTime;

class ScheduleService
{
    protected $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getAllSchedules()
    {
        return $this->scheduleRepository->all();
    }

    public function createSchedule(array $data)
    {
        $authUser = auth()->id();
        $request['user_id'] = $authUser;
        return $this->scheduleRepository->create($data);
    }

    public function createCustomScheduleForLoggedInUser(array $data)
    {
        $authUser = auth()->user();
        $data['user_id'] = $authUser->id;

        // Validar la duración mínima del horario
        $startTime = new DateTime($data['start_time']);
        $endTime = new DateTime($data['end_time']);
        $duration = $endTime->diff($startTime)->h;

        if ($duration < 5) {
            // Lanzar una excepción si la duración es menor a 5 horas
            throw new \Exception('La duración mínima del horario debe ser de 5 horas.');
        }
        // Reemplazar el horario actual del usuario si existe uno
        if ($authUser->schedule) {
            $authUser->schedule->delete();
        }

        return $this->scheduleRepository->create($data);
    }

    public function checkAttendance($user, DateTime $currentTime)
    {

        // Obtener el día de la semana actual
        $dayOfWeek = $currentTime->format('w');

        // Obtener el horario del usuario para el día actual
        $schedule = Schedule::where('day_of_week', $dayOfWeek)
            ->where('user_id', $user->id)
            ->first();
        if (!$schedule) {
            // No tiene horario definido para el día actual
            return 'Sin horario';
        }
        if ($currentTime < $schedule->start_time) {
            return 'A tiempo';
        } elseif ($currentTime > $schedule->start_time && $currentTime < $schedule->end_time) {
            return 'Llegó tarde';
        } elseif ($currentTime > $schedule->end_time) {
            return 'Salió temprano';
        } else {
            return 'Hora correcta de salida';
        }
    }
}

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
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
        $data['user_id'] = $authUser;

        // Validar la duración mínima del horario
        $startTime = new DateTime($data['start_time']);
        $endTime = new DateTime($data['end_time']);
        $duration = $endTime->diff($startTime)->h;

        if ($duration < 5) {
            // Lanzar una excepción si la duración es menor a 5 horas
            throw new \Exception('La duración mínima del horario debe ser de 5 horas.');
        } else {
            return $this->scheduleRepository->create($data);
        }
    }
}

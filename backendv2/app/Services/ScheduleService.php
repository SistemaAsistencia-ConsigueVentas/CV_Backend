<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ScheduleRepositories\ScheduleRepositoryInterface;

class ScheduleService
{
    protected $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    
}
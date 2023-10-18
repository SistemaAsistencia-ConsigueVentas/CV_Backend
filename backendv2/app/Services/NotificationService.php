<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use App\Repositories\NotificationRepositories\NotificationRepositoryInterface;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function sanctionUserAbsences($userId): void
    {
        $absencesCount = $this->notificationRepository->countUserAbsences($userId);

        if($absencesCount >= 4)
        {
            $attendance = Attendance::where('user_id', $userId)->first(); 

            $attendance->attendance = false;
            $attendance->save();

            $user = User::find($userId);

            $user->status = false;
            $user->save();
        };
    }

    public function sanctionUserDelays($userId): void
    {
        $delaysCount = $this->notificationRepository->countUserDelays($userId);

        if($delaysCount >= 13)
        {
            $attendance = Attendance::where('user_id', $userId)->first(); 

            $attendance->attendance = false;
            $attendance->save();
        };
    }
}

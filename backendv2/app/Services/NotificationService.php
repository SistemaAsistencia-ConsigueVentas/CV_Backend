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

    // Acción para sancionar al usuario por un exceso de ausencias
    public function sanctionUserAbsences($userId): void
    {
        // Obtener la cantidad de ausencias del usuario
        $absencesCount = $this->notificationRepository->countUserAbsences($userId);

        // Verificar si el usuario tiene 4 o más ausencias
        if($absencesCount >= 4)
        {
            // Obtener el registro de asistencia del usuario
            $attendance = Attendance::where('user_id', $userId)->first(); 

            // Marcar al usuario como ausente y guardar el registro de asistencia
            $attendance->attendance = false;
            $attendance->save();

            // Encontrar el usuario y marcarlo como inactivo
            $user = User::find($userId);
            $user->status = false;
            $user->save();
        }
    }

    // Acción para sancionar al usuario por un exceso de tardanzas
    public function sanctionUserDelays($userId): void
    {
        // Obtener la cantidad de tardanzas del usuario
        $delaysCount = $this->notificationRepository->countUserDelays($userId);

        // Verificar si el usuario tiene 13 o más tardanzas
        if($delaysCount >= 13)
        {
            // Obtener el registro de asistencia del usuario
            $attendance = Attendance::where('user_id', $userId)->first(); 

            // Marcar al usuario como con tardanzas y ausente, y guardar el registro de asistencia
            $attendance->attendance = false;
            $attendance->save();
        }
    }
}

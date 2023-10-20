<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Attendance;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\NotificationRepositories\NotificationRepositoryInterface;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    // lógica para verificar si el usuario debe ser bloqueado.
    public function isUserBlockedForAbsences($userId): bool
    {
        // Obtener la cantidad de ausencias y tardanzas del usuario
        $absencesCount = $this->notificationRepository->countUserAbsences($userId);
        $delaysCount = $this->notificationRepository->countUserDelays($userId);

        // Verificar si el usuario tiene 4 o mas ausencias y 13 o más tardanzas
        if ($absencesCount > 3 || $delaysCount >= 13) {

            $notification = new Notification();
            $notification->user_id = $userId; // Asigna el ID del usuario a la clave foránea
            $notification->message = 'Usuario deshabilitado por exceder el límite de inasistencias.'; // Agrega el mensaje que desees
            $notification->save();

            // Encontrar el usuario y marcarlo como inactivo
            $user = User::find($userId);
            $user->status = false;
            $user->save();

            return true;
        }

        return false;
    }
}

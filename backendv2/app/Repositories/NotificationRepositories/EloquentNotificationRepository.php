<?php

declare(strict_types=1);

namespace App\Repositories\NotificationRepositories;

use App\Models\Attendance;
use App\Models\Notification;

class EloquentNotificationRepository implements NotificationRepositoryInterface
{

    public function all(): iterable
    {
        return Notification::all();
    }

    public function find(int $id): ?Notification
    {
        return Notification::find($id);
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $notification = $this->find($id);
        if ($notification) {
            return $notification->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $notification = $this->find($id);
        if ($notification) {
            return $notification->delete();
        }
        return false;
    }

    // Cuenta la cantidad de ausencias de un usuario identificado por su ID.
    public function countUserAbsences(int $userId): int
    {
        return Attendance::where('attendance', false)
            ->where('justification', false)
            ->where('justification', false)
            ->whereHas('user', function ($query) use ($userId) {
                $query->where('id', $userId);
            })
            ->count();
    }

    // Cuenta la cantidad de tardanzas de un usuario identificado por su ID
    public function countUserDelays(int $userId): int
    {
        return Attendance::where('delay', true)
            ->where('justification', false) 
            ->where('justification', false) 
            ->whereHas('user', function ($query) use ($userId) {
                $query->where('id', $userId);
            })
            ->count();
    }
}

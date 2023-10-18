<?php

declare(strict_types=1);

namespace App\Repositories\NotificationRepositories;

use App\Models\Notification;

interface NotificationRepositoryInterface {
    public function all(): iterable;
    public function find(int $id): ?Notification;
    public function create(array $data): Notification;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function countUserAbsences(int $userId): int;
    public function countUserDelays(int $userId): int;
}
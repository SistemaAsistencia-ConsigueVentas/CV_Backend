<?php

declare(strict_types=1);

namespace App\Repositories\AttendanceRepositories;

use App\Models\Attendance;

interface AttendanceRepositoryInterface {
    public function all(): iterable;
    public function find(int $id): ?Attendance;
    public function create(array $data): Attendance;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}

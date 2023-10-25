<?php

declare(strict_types=1);

namespace App\Repositories\ScheduleRepositories;

use App\Models\Schedule;

interface ScheduleRepositoryInterface {
    public function all(): iterable;
    public function find(int $id): ?Schedule;
    public function create(array $data): Schedule;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function createCustomScheduleForLoggedInUser(array $data) : Schedule;
}

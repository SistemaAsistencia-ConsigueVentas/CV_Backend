<?php

declare(strict_types=1);

namespace App\Repositories\ScheduleRepositories;

use App\Models\Schedule;

class EloquentScheduleRepository implements ScheduleRepositoryInterface {

    public function all(): iterable {
        return Schedule::all();
    }

    public function find(int $id): ?Schedule {
        return Schedule::find($id);
    }

    public function create(array $data): Schedule {
        return Schedule::create($data);
    }

    public function update(int $id, array $data): bool {
        $schedule = $this->find($id);
        if ($schedule) {
            return $schedule->update($data);
        }
        return false;
    }

    public function delete(int $id): bool {
        $schedule = $this->find($id);
        if ($schedule) {
            return $schedule->delete();
        }
        return false;
    }

    public function createCustomScheduleForLoggedInUser(array $data): Schedule {
        $schedule = new Schedule($data);
        $schedule->save();
        return $schedule;
    }
}
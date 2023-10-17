<?php

declare(strict_types=1);

namespace App\Repositories\AttendanceRepositories;

use App\Models\Attendance;

class EloquentAttendanceRepository implements AttendanceRepositoryInterface {

    public function all(): iterable {
        return Attendance::all();
    }

    public function find(int $id): ?Attendance {
        return Attendance::find($id);
    }

    public function create(array $data): Attendance {
        return Attendance::create($data);
    }

    public function update(int $id, array $data): bool {
        $attendance = $this->find($id);
        if ($attendance) {
            return $attendance->update($data);
        }
        return false;
    }

    public function delete(int $id): bool {
        $attendance = $this->find($id);
        if ($attendance) {
            return $attendance->delete();
        }
        return false;
    }
}
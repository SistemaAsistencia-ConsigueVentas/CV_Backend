<?php

declare(strict_types=1);

namespace App\Repositories\ProfileRepositories;

use App\Models\Position;
use App\Repositories\ProfileRepositories\PositionRepositoryInterface;

class EloquentPositionRepository implements PositionRepositoryInterface {

    public function all(): iterable {
        return Position::all();
    }

    public function find(int $id): ?Position {
        return Position::find($id);
    }

    public function create(array $data): Position {
        return Position::create($data);
    }

    public function update(int $id, array $data): bool {
        $profile = $this->find($id);
        if ($profile) {
            return $profile->update($data);
        }
        return false;
    }

    public function delete(int $id): bool {
        $profile = $this->find($id);
        if ($profile) {
            return $profile->delete();
        }
        return false;
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories\JustificationRepositories;

use App\Models\Justification;

class EloquentJustificationRepository implements JustificationRepositoryInterface {

    public function all(): iterable {
        return Justification::all();
    }

    public function find(int $id): ?Justification {
        return Justification::find($id);
    }

    public function create(array $data): Justification {
        return Justification::create($data);
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
<?php

declare(strict_types=1);

namespace App\Repositories\UserRepositories;

use App\Models\User;

class EloquentUserRepository implements UserRepositoryInterface {

    public function all(): iterable {
        return User::all();
    }

    public function find(int $id): ?User {
        return User::find($id);
    }

    public function create(array $data): User {
        return User::create($data);
    }

    public function update(int $id, array $data): bool {
        $user = $this->find($id);
        if ($user) {
            return $user->update($data);
        }
        return false;
    }

    public function delete(int $id): bool {
        $user = $this->find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
<?php

declare(strict_types=1);

namespace App\Repositories\UserRepositories;

use App\Models\User;

interface UserRepositoryInterface {
    public function all(): iterable;
    public function find(int $id): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}

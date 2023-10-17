<?php

declare(strict_types=1);

namespace App\Repositories\ProfileRepositories;

use App\Models\Position;

interface PositionRepositoryInterface {
    public function all(): iterable;
    public function find(int $id): ?Position;
    public function create(array $data): Position;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
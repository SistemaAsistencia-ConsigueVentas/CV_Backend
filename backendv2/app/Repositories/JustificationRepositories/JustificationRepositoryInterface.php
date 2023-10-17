<?php

declare(strict_types=1);

namespace App\Repositories\JustificationRepositories;

use App\Models\Justification;

interface JustificationRepositoryInterface {
    public function all(): iterable;
    public function find(int $id): ?Justification;
    public function create(array $data): Justification;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
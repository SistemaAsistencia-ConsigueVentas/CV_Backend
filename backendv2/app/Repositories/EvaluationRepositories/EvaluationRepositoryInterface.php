<?php

declare(strict_types=1);

namespace App\Repositories\EvaluationRepositories;

use App\Models\Evaluation;

interface EvaluationRepositoryInterface {
    public function all(): iterable;
    public function find(int $id): ?Evaluation;
    public function create(array $data): Evaluation;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
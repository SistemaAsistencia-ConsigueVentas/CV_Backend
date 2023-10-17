<?php

declare(strict_types=1);

namespace App\Repositories\EvaluationRepositories;

use App\Models\Evaluation;

class EloquentEvaluationRepository implements EvaluationRepositoryInterface {

    public function all(): iterable {
        return Evaluation::all();
    }

    public function find(int $id): ?Evaluation{
        return Evaluation::find($id);
    }

    public function create(array $data): Evaluation {
        return Evaluation::create($data);
    }

    public function update(int $id, array $data): bool {
        $evaluations = $this->find($id);
        if ($evaluations) {
            return $evaluations->update($data);
        }
        return false;
    }

    public function delete(int $id): bool {
        $evaluations = $this->find($id);
        if ($evaluations) {
            return $evaluations->delete();
        }
        return false;
    }
}
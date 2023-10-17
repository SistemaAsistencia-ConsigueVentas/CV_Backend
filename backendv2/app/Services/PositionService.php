<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Core;
use App\Models\Position;
use App\Repositories\ProfileRepositories\PositionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PositionService {
    protected $positionRepository;
    public function __construct(PositionRepositoryInterface $positionRepository) {
        $this->positionRepository = $positionRepository;
    }

    public function getAllPositions() {
        try {
            $cores = Position::with('core.department')->get();
            return [$cores];
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener todas las posiciones.', 500);
        }
    }

    public function createPosition(array $data) {
        try {
            if (Core::find($data['core_id'])) {
                return $this->positionRepository->create($data);
            }
            return null;
        } catch (\Exception $e) {
            throw new \Exception('Error al crear la posición.', 500);
        }
    }
    
    public function updatePosition(int $id, array $data) {
        try {
            if (Core::find($data['core_id'])) {
                return $this->positionRepository->update($id, $data);
            }
            return false;
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Posición no encontrada');
        } catch (\Exception $e) {
            throw new \Exception('Error al actualizar la posición.', 500);
        }
    }
    
    public function deletePosition(int $id) {
        try {
            return $this->positionRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Posición no encontrada');
        } catch (\Exception $e) {
            throw new \Exception('Error al eliminar la posición.', 500);
        }
    }
}

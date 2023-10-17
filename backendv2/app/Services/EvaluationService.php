<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Evaluation;
use App\Models\ModelHasRole;
use App\Models\Note;
use App\Repositories\EvaluationRepositories\EvaluationRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EvaluationService {
    protected $evaluationRepository;
    public function __construct(EvaluationRepositoryInterface $evaluationRepository) {
        $this->evaluationRepository = $evaluationRepository;
    }

    public function getAllEvaluations() {
        try {
            $eva = Evaluation::with(['user'])->get();
            return $eva;
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener todas las evaluaciones.', 500);
        }
    }

    public function createEvaluations(array $data) {
        try {
            $date = date('Y-m-d');
            $data['date'] = $date; 
            return $this->evaluationRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Error al crear las evaluaciones.', 500);
        }
    }
    
    public function storeEvaluationNotes(array $data, $id){
        try {
            // Buscar si ya existen notas para la evaluación con el ID dado
            $existingEvaluation = Evaluation::find($id);
            //$user_id = auth()->id();
            if (!$existingEvaluation) {
                // Ya existen notas para esta evaluación, devolver un mensaje de error
                throw new ModelNotFoundException('Esta evaluacion no existe');
            } else {
                //Guardamos la información en la evaluacion
                $existingEvaluation->softskills = $data['softskills'];
                $existingEvaluation->performance = $data['performance'];
                $existingEvaluation->hardskills = $data['hardskills'];
                $existingEvaluation->autoevaluation = $data['autoevaluation'];
                //Accedemos al rol del usuario logueado
                $roleId = ModelHasRole::where('model_id', $existingEvaluation->user_id)->get('role_id');
                $rol = $roleId[0]->role_id;
                //print($rol);
                //Calculamos el promedio en base a los roles del usuario logueado
                if ($rol == 2 || $rol == 1 || $rol == 4) {
                    $prom = $existingEvaluation->autoevaluation;
                } elseif ($rol == 3) {
                    $prom = ($existingEvaluation->hardskills + $existingEvaluation->performance +  $existingEvaluation->softskills) / 3;
                } 
                //Guardamos el promedio en base de datos
                $existingEvaluation->promedio = $prom;
                //Guardamos la informacion en base de datos
                $existingEvaluation->save();
                //Retornamos la respuesta en formato JSON
                return ['message' => 'Notas de evaluación registradas con éxito', 'data' => $existingEvaluation];
            }   
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Esta evaluacion no existe');
        } catch (\Exception $e) {
            throw new \Exception('Error al almacenar las notas de la evaluación.', 500);
        }
    }
    
}

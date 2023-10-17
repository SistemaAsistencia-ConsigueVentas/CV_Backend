<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Services\EvaluationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EvaluationController extends Controller
{     
    protected $evaluationService;
    public function __construct(EvaluationService $evaluationService) {
        $this->evaluationService = $evaluationService;
    }
    public function getEvaluations()
    {
        try {
            return $this->evaluationService->getAllEvaluations();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las evaluaciones.'], 500);
        }
    }
    public function createEvaluation(Request $request)
    {
        try {
            $evaluation = $this->evaluationService->createEvaluations($request->all());
            return response()->json(['message' => 'Evaluacion registrada con exito', 'data' => $evaluation]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la evaluacion.'], 500);
        }
    }

    public function searchEvaluationById($id){
        $evaluation = Evaluation::find($id);
        if ($evaluation) { 
            return response()->json(['data' => $evaluation]);
        } else {  
            return response()->json(['Esta evaluacion no existe, porfavor ingrese otro ID']);
        }
    }

    public function searchEvaluationByUser($userId){
        $evaluation = Evaluation::where('user_id', $userId)->get();

        if (!is_null($evaluation)) { 
            return response()->json(['data' => $evaluation]);
        } else {  
            return response()->json(['message' => 'Esta usuario no tiene asignado ninguna evaluacion']);
        }
    }

    public function storeNotes(Request $request, $id)
    {
        try {
            $evaluation = $this->evaluationService->storeEvaluationNotes($request->all(), $id);
            return $evaluation;
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Evaluacion no encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al almacenar las notas de la evaluacion.'], 500);
        }
    }
}

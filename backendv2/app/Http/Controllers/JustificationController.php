<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Justification;
use App\Services\JustificationService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class JustificationController extends Controller
{
    protected $justificationService;
    public function __construct(JustificationService $justificationService)
    {
        $this->justificationService = $justificationService;
    }

    public function getJustifications(Request $request) {
        try {
            $justifications = $this->justificationService->getJustifications($request->all());
            return response()->json($justifications);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las justificaciones.'], 500);
        }
    }

    public function createJustifications(Request $request) {
        try {
            $justification = $this->justificationService->createJustification($request->all());
            return response()->json(['message' => 'Justificacion creada exitosamente.', 'data' => $justification], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la justificacion.'], 500);
        }
    }

    public function acceptJustifications($id) {
        try {
            $justification = $this->justificationService->acceptJustification($id);
            return response()->json(['message' => $justification], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Justificacion no encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al aceptar la justificacion.'], 500);
        }
    }

    public function declineJustifications(Request $request, $id) {
        try {
            $justification = $this->justificationService->declineJustification($request, $id);
            return response()->json(['message' => $justification], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Justificacion no encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al rechazar la justificacion.'], 500);
        }
    }
}

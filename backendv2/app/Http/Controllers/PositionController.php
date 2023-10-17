<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\Services\PositionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class PositionController extends Controller
{
    protected $positionService;
    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public function getProfiles()
    {
        try {
            $profiles = $this->positionService->getAllPositions();
            $combinedProfiles = array_reduce($profiles, function ($carry, $profileCollection) {
                return array_merge($carry, $profileCollection->all());
            }, []);
            return $combinedProfiles;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los perfiles.'], 500);
        }
    }

    public function createProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255|unique:positions',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }
            $profile = $this->positionService->createPosition($request->all());
            if (!$profile) {
                return response()->json(['message' => 'Nucleo no encontrado.'], 404);
            }
            return response()->json(['message' => 'Perfil creado exitosamente.', 'data' => $profile], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el perfil.'], 500);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'string',
                    'max:255',
                    Rule::unique('positions')->ignore($id),
                ],
                'department_id' => 'integer|exists:departments,id',
                'core_id' => 'integer|exists:cores,id',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }
            $updated = $this->positionService->updatePosition($id, $request->all());
            if (!$updated) {
                return response()->json(['message' => 'Nucleo no encontrado o perfil no encontrado.'], 404);
            }
            return response()->json(['message' => 'Perfil actualizado exitosamente.']);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Nucleo no encontrado o perfil no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el perfil.'], 500);
        }
    }
    
    public function deleteProfile($id)
    {
        try {
            $deleted = $this->positionService->deletePosition($id);
            if (!$deleted) {
                return response()->json(['message' => 'Perfil no encontrado.'], 404);
            }
            return response()->json(['message' => 'Perfil eliminado exitosamente.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Perfil no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el perfil.'], 500);
        }
    }
}
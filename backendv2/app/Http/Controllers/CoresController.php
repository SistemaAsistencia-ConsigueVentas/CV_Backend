<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Core;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class CoresController extends Controller
{
    // Mostrar todos los nucleos
    public function getCores()
    {
        try {
            $cores = Core::with('department')->get();
            return response()->json($cores);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los nucleos.'], 500);
        }
    }

    // Crear un nuevo nucleo en la base de datos
    public function createCore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255|unique:cores',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }
            //Creamos un nuevo objeto Cores
            $core = new Core();
            //Setear los valores en la tabla Cores
            $core->name = $request->input('name');
            //Validamos que el departamento exista en la base de datos
            if (Department::find($request->input('department_id'))) {
                //Setear los valores en la tabla Cores
                $core->department_id = $request->input('department_id');
                //Guardamos los valores en la tabla Cores
                $core->save();
            } else {
                return response()->json(['message' => 'Departamento no encontrado.'], 404);
            }
            //Retornamos la respuesta en formato JSON
            return response()->json(['message' => 'Nucleo creado exitosamente.', 'data' => $core], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Departamento no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el nucleo.'], 500);
        }
    }

    public function updateCore(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'string',
                    'max:255',
                    Rule::unique('cores')->ignore($id),
                ],
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }
            //Buscamos el departmento por id
            $core = Core::find($id);
            //Validamos que el departamento exista en la base de datos
            if (!$core) {
                return response()->json(['message' => 'Nucleo no encontrado.'], 404);
            } else {
                //Setear los valores en la tabla Departments
                $core->name = $request->input('name');
                //Validamos que el departamento exista en la base de datos
                if (Department::find($request->input('department_id'))) {
                    //Setear los valores en la tabla Cores
                    $core->department_id = $request->input('department_id');
                    //Guardamos los valores en la tabla Cores
                    $core->save();
                } else {
                    return response()->json(['message' => 'Departamento no encontrado.'], 404);
                }
                //Actualizamos los valores en la tabla Departments
                $core->update();
                //Retornamos la respuesta en formato JSON
                return response()->json(['message' => 'Nucleo actualizado exitosamente.', 'data' => $core]);
            }
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Nucleo no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el nucleo.'], 500);
        }
    }
    
    // Borrar un nucleo en la base de datos
    public function deleteCore($id)
    {
        try {
            $core = Core::find($id);
            //Validamos que el nucleo exista en la base de datos
            if (!$core) {
                return response()->json(['message' => 'Nucleo no encontrado.'], 404);
            } else {
                //Borramos el nucleo
                $core->delete();
                //Retornamos la respuesta en formato JSON
                return response()->json(['message' => 'Nucleo eliminado exitosamente.']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el nucleo.'], 500);
        }
    }
}
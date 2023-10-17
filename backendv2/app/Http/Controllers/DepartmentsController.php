<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class DepartmentsController extends Controller
{
    // Mostrar todos los departamentos
    public function getDepartments()
    {
        try {
            $departments = Department::all();
            return response()->json($departments);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los departamentos.'], 500);
        }
    }

    // Crear un nuevo departamento en la base de datos
    public function createDepartment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255|unique:departments',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }
            //Creamos un nuevo objeto Departaments
            $department = new Department();
            //Setear los valores en la tabla Departments
            $department->name = $request->input('name');
            //Guardamos los valores en la tabla Departments
            $department->save();
            //Retornamos la respuesta en formato JSON
            return response()->json(['message' => 'Departamento creado exitosamente.', 'data' => $department], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el departamento.'], 500);
        }
    }

    // Actualizar un departamento en la base de datos
    public function updateDepartment(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255|unique:departments,name,' . $id,
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }
            //Buscamos el departmento por id
            $department = Department::find($id);
            //Validamos que el departamento exista en la base de datos
            if (!$department) {
                return response()->json(['message' => 'Departamento no encontrado.'], 404);
            } else {
                //Setear los valores en la tabla Departments
                $department->name = $request->input('name');
                //Actualizamos los valores en la tabla Departments
                $department->update();
                //Retornamos la respuesta en formato JSON
                return response()->json(['message' => 'Departamento actualizado exitosamente.', 'data' => $department]);
            }
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Departamento no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el departamento.'], 500);
        }
    }
    
    public function deleteDepartment($id)
    {
        try {
            $department = Department::find($id);
            //Validamos que el departamento exista en la base de datos
            if (!$department) {
                return response()->json(['message' => 'Departamento no encontrado.'], 404);
            } else {
                //Borramos el departmento
                $department->delete();
                //Retornamos la respuesta en formato JSON
                return response()->json(['message' => 'Departamento eliminado exitosamente.']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el departamento.'], 500);
        }
    }
}

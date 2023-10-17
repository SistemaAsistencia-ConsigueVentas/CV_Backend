<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function getReports()
    {
        // Selecciona datos de múltiples tablas
        $reportData = Department::select('departments.name as department_name', 'cores.name as core_name', 'positions.name as profile_name')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.attendance = TRUE THEN 1 ELSE 0 END), 0) as profile_attendance_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.attendance = FALSE THEN 1 ELSE 0 END), 0) as profile_absence_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.delay = TRUE THEN 1 ELSE 0 END), 0) as profile_delay_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.justification = TRUE THEN 1 ELSE 0 END), 0) as profile_justification_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.attendance = FALSE THEN 1 ELSE 0 END), 0) as core_absence_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.delay = TRUE THEN 1 ELSE 0 END), 0) as core_delay_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.justification = TRUE THEN 1 ELSE 0 END), 0) as core_justification_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.attendance = FALSE THEN 1 ELSE 0 END), 0) as department_absence_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.delay = TRUE THEN 1 ELSE 0 END), 0) as department_delay_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN attendances.justification = TRUE THEN 1 ELSE 0 END), 0) as department_justification_count')

            // Realiza uniones (joins) entre tablas
            ->leftJoin('cores', 'departments.id', '=', 'cores.department_id')
            ->leftJoin('positions', 'cores.id', '=', 'positions.core_id')
            ->leftJoin('users', 'positions.id', '=', 'users.position_id')
            ->leftJoin('attendances', 'users.id', '=', 'attendances.user_id')

            // Agrupa los resultados por nombre de departamento, nombre de núcleo y nombre de posición.
            ->groupBy('departments.name', 'cores.name', 'positions.name')

            // Ordena los resultados por nombre de departamento, nombre de núcleo y nombre de posición.
            ->orderBy('departments.name')
            ->orderBy('cores.name')
            ->orderBy('positions.name')

            // Obtiene los resultados
            ->get();
        
        // Devuelve los resultados en formato JSON.
        return response()->json(['data' => $reportData]);
    }
}
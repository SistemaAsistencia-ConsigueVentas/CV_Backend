<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{

    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function getAttendances(Request $request)
    {
        try {
            $filters = $request->all();
            $attendances = $this->attendanceService->getFilteredAttendances($filters);
            return response()->json($attendances);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createAttendance(Request $request)
    {
        try {
            $attendance = $this->attendanceService->store($request->all());
            return response()->json(['message' => 'Asistencia marcada con éxito', 'data' => $attendance]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAttendancesByID(Request $request)
    {
        try {
            //Recogemos el ID del usuario logeado
            $user_id = auth()->id();
            $today = date('Y-m-d');

            // Obtener el registro de asistencia del usuario para el usuario actualmente logeado
            // whereDate() para filtrar por fecha en lugar de por fecha y hora
            $attendance = Attendance::where('user_id', $user_id)->whereDate('date', $today)->get();

            //Retornamos la respuesta en formato JSON
            return response()->json(['attendance' => $attendance]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callDatabaseProcedure()
    {
        DB::statement('select llenar_attendances_user_id();');
    }
}

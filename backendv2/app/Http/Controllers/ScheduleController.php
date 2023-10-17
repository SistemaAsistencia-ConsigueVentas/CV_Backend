<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getSchedules()
    {
        try {
            return Schedule::get();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los horarios.'], 500);
        }
    }

    public function createSchedule(Request $request)
    {
        try {
            $authUser = auth()->id();
            $request['user_id'] = $authUser; 
            $schedule = Schedule::create($request->all());
            return response()->json(['message' => 'Horario creado exitosamente.', 'data' => $schedule], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el horario.'], 500);
        }
    }
    
    public function checkAttendance(DateTime $currentTime)
    {
        try {
            $authUser = auth()->user();
            print($currentTime->format('w'));
            // Obtener el horario del usuario para el día actual
            $schedule = Schedule::where('day_of_week', $currentTime->format('w'))->where('user_id', $authUser->id)->first();
            if (!$schedule) {
                // No tiene horario definido para el día actual
                return 'Sin horario';
            }
            if ($currentTime < $schedule->start_time) {
                return 'A tiempo';
            } elseif ($currentTime > $schedule->start_time && $currentTime < $schedule->end_time) {
                return 'Llegó tarde';
            } elseif ($currentTime > $schedule->end_time) {
                return 'Salió temprano';
            } else {
                return 'Hora correcta de salida';
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Horario no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al verificar la asistencia.'], 500);
        }
    }
    
}

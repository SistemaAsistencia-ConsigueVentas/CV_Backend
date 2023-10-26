<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function getSchedules()
    {
        try {
            $schedules = $this->scheduleService->getAllSchedules();
            return response()->json(['message' => 'Horarios obtenidos exitosamente.', 'data' => $schedules], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los horarios.'], 500);
        }
    }

    public function createSchedule(Request $request)
    {
        try {
            $schedule = $this->scheduleService->createSchedule($request->all());
            return response()->json(['message' => 'Horario creado exitosamente.', 'data' => $schedule], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el horario.'], 500);
        }
    }

    //UPDATE

    //DELETE
}

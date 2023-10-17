<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;


class BirthdayController extends Controller
{
    //
    public function getbirthday(){
        return response()->json(User::all('birthday'),200);
    }

    public function detailsbirthdayMonth(Request $request) {
        try {
            $month = $request->input('m'); // Obtener el valor del parámetro "m" (mes)
            $day = $request->input('d');   // Obtener el valor del parámetro "d" (día), si está presente
            $query = User::whereMonth('birthday', $month)->where('status', true)->with('position.core.department');
            
            if (!empty($day)) {
                $query->whereDay('birthday', $day);
            }

            $upcomingBirthdays = $query->orderByRaw('EXTRACT(DAY FROM birthday)')->get(); // 'DAY(birthday)'
            
            // Agregar la URL de la imagen a cada usuario
            foreach ($upcomingBirthdays as $user) {
                $user->image_url = $user->getImageUrlAttribute();
            }

            return response()->json($upcomingBirthdays, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No se encontraron usuarios con los cumpleaños solicitados'], 404);
        } 
    }
    
    public function getUpcomingBirthdaysWithUsers() {
        try {
            $currentDate = now();
            $userShift = auth()->user()->shift; // Obtener el turno del usuario logeado
            $upcomingBirthdays = User::whereMonth('birthday', $currentDate->month)->with('position.core.department')
                ->whereDay('birthday', '>=', $currentDate->day)
                ->where('shift', $userShift) // Filtrar por turno
                ->where('status', true)
                ->orderByRaw('EXTRACT(DAY FROM birthday)') // 'DAY(birthday)'
                ->get();

            // Agregar la URL de la imagen a cada usuario
            foreach ($upcomingBirthdays as $user) {
                $user->image_url = $user->getImageUrlAttribute();
            }

            return response()->json($upcomingBirthdays, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No se encontraron usuarios con los cumpleaños proximos'], 404);
        } 
    }

}
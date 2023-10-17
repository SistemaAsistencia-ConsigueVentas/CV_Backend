<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Attendance;
use App\Repositories\AttendanceRepositories\AttendanceRepositoryInterface;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Justification;

class AttendanceService {
    protected $attendanceRepository;
    public function __construct(AttendanceRepositoryInterface $attendanceRepository) {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function getFilteredAttendances(array $filters): LengthAwarePaginator
    {
        try {
            return Attendance::filter($filters)->paginate(10);
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener las asistencias.', 500);
        }
    }

    private function isLateForCheckIn($checkInTime) {
        $currentTime = now();
        if ($currentTime->format('H:i') > '13:00') {
            $checkInLimit = new DateTime('14:11', new DateTimeZone('America/Lima'));
        } else {
            $checkInLimit = new DateTime('08:11', new DateTimeZone('America/Lima'));
        }
        $checkInTime = new DateTime($checkInTime, new DateTimeZone('America/Lima'));
        return $checkInTime > $checkInLimit;
    }

    private function uploadImage($image) {
        try {
            // Subir imagen al servidor
            $file = $image;
            $folderName = date("Y-m-d");
            $path = "attendances/" . $folderName;
            $filename = time() . "-" . $file->getClientOriginalName();
            $file->move($path, $filename);
            return $path . "/" . $filename;
        } catch (\Exception $e) {
            throw new \Exception('Error al subir la imagen.', 500);
        }
    }

    private function hasJustification() {
        try {
            $flag = 2;
            $today = date('Y-m-d');
            $authUser = auth()->user();
            $justificationExists = Justification::where('user_id', $authUser->id)
                ->whereDate('justification_date', $today) //Falta condicional del status != 3
                ->first('type');
            if (is_null($justificationExists)){
                return $flag;
            } else {
                return $justificationExists->type; // 0 | 1
            }
        } catch (\Exception $e) {
            throw new \Exception('Error al verificar la justificación.', 500);
        }
    }

    public function store(array $data)
    {
        try {
            $authUser = auth()->id();
            $currentTime = now();
            $today = date('Y-m-d');
            $attendance = Attendance::where('user_id', $authUser)
                ->whereDate('date', $today)
                ->firstOrNew();
            if ($attendance->attendance == 0 && $attendance->delay == 0) { //Validacion de base de datos
                $this->updateCheckIn($attendance, $currentTime, $data['admission_image'], $authUser);
            } else {
                $this->updateCheckOut($attendance, $currentTime, $data['departure_image']);
            }
            return $attendance;
        } catch (\Exception $e) {
            throw new \Exception('Error al guardar la asistencia.', 500);
        }
    }

    protected function updateCheckIn($attendance, $currentTime, $imagePath, $authUser)
    {
        try {
            $attendance->admission_time = $currentTime->format('H:i');
            $attendance->admission_image = $this->uploadImage($imagePath);
            $attendance->user_id = $authUser;
            $attendance->date = $currentTime->format('Y-m-d');
            if ($this->isLateForCheckIn($attendance->admission_time)) {
                $type = $this->hasJustification();
                if ($type == 2) {
                    $attendance->delay = 1;
                } else {
                    $attendance->justification = 1;
                    $attendance->delay = 1;
                }
            } else {
                $attendance->attendance = 1;
            }
            $attendance->save();
        } catch (\Exception $e) {
            throw new \Exception('Error al actualizar el check-in.', 500);
        }
    }
    
    protected function updateCheckOut($attendance, $currentTime, $imagePath)
    {
        try {
            $attendance->departure_time = $currentTime->format('H:i');
            $attendance->departure_image = $this->uploadImage($imagePath);
            $attendance->save();
        } catch (\Exception $e) {
            throw new \Exception('Error al actualizar el check-out.', 500);
        }
    }
}

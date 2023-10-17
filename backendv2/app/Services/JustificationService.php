<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Attendance;
use App\Models\Justification;
use App\Repositories\JustificationRepositories\JustificationRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JustificationService {
    protected $justificationRepository;
    public function __construct(JustificationRepositoryInterface $justificationRepository) {
        $this->justificationRepository = $justificationRepository;
    }

    public function getJustifications(array $filters)
    {
        try {
            $query = Justification::with('User.position.core.department', 'actionByUser:id,name,surname');
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (isset($filters['user'])) {
                $query->where('user_id', Auth::user()->id);
            }
            if (isset($filters['exclude_user'])) {
                $query->where('user_id', '!=' , Auth::user()->id);
            }
            if (isset($filters['shift'])) {
                $query->whereHas('User.position', function ($q) use ($filters) {
                    $q->where('shift', $filters['shift']);
                });
            }
            if (isset($filters['id'])) {
                $justification = $query->find($filters['id']);
                if (!$justification) {
                    throw new ModelNotFoundException('Justificación no encontrada');
                }
                return  $justification;
            }
            // Filtrar por nombre o apellido si se proporciona
            if (isset($filters['name'])) {
                $query->whereHas('User', function ($q) use ($filters) {
                    $q->where('name', 'LIKE', '%' . $filters['name'] . '%')
                        ->orWhere('surname', 'LIKE', '%' . $filters['name'] . '%');
                });
            }

            $query->orderBy('created_at', 'desc');
            $justifications = $query->paginate(6);
            $declines = Justification::where('status', '2')->count();
            $process = Justification::where('status', '3')->count();
            $accept = Justification::where('status', '1')->count();
            $absence = Justification::where('type', '0')->count();
            $delay = Justification::where('type', '1')->count();

            $justifications = collect($justifications)->map(function ($justification) {
                $justification->user->image_url = $justification->user->getImageUrlAttribute();
                return $justification;
            });

            return ['Justifications' => $justifications,
                'rechazados' => $declines,
                'proceso' => $process,
                'aceptados' => $accept,
                'faltas' => $absence,
                'delay' => $delay];
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener las justificaciones.', 500);
        }
    }

    private function uploadImage($image) {
        try {
            // Subir imagen al servidor
            $file = $image;
            $folderName = date("Y-m-d");
            $path = "justifications/" . $folderName;
            $filename = time() . "-" . $file->getClientOriginalName();
            $file->move($path, $filename);
            return $path . "/" . $filename;
        } catch (\Exception $e) {
            throw new \Exception('Error al subir la imagen.', 500);
        }
    }

    public function createJustification(array $data) {
        try {
            //Por default el status == 3 (En Proceso)
            $data["status"] = 3;
            //Por default el usuario logueado
            $user_id = auth()->id();
            $data["user_id"] = $user_id;
            //Redireccion de imagen a carpeta local
            $data['evidence'] = $this->uploadImage($data['evidence']);
            return $this->justificationRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Error al crear la justificación.', 500);
        }
    }

    public function acceptJustification($id) {
        try {
            $actionByUserId = auth()->id();
            $justification = Justification::find($id);
            if (!$justification) {
                throw new ModelNotFoundException('Justificación no encontrada');
            }
            $date = $justification->justification_date;
            $user = $justification->user_id;
            // Verificar si ya existe un registro de asistencia
            $attendance = Attendance::where('user_id', $user)->where('date', $date)->first();
            if ($attendance) {
                if ($justification->type == '0') {
                    $attendance->update(['attendance' => '0', 'justification' => '1']);
                } else {
                    $attendance->update(['justification' => '1']);
                }
            } else {
                $attendanceData = [
                    'user_id' => $user,
                    'date' => $date,
                    'justification' => '1',
                ];
                if ($justification->type == '0') {
                    $attendanceData['attendance'] = '0';
                } else {
                    $attendanceData['delay'] = '1';
                }
                Attendance::create($attendanceData);
            }
            $justification->update(['status' => '1', 'action_by' => $actionByUserId]);
            return "Justificación aceptada con éxito";
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Justificación no encontrada');
        } catch (\Exception $e) {
            throw new \Exception('Error al aceptar la justificación.', 500);
        }
    }

    public function declineJustification(Request $request, $id) {
        try {
            $actionByUserId = auth()->id();
            $justification = Justification::find($id);
            if ($justification) {
                if ($justification->status == 2 || $justification->status == 1) {
                    return 'Esta justificación ya ha sido declinada o aceptada';
                } else {
                    $justification->update([
                        'status' => '2',
                        'reason_decline' => $request->reason_decline,
                        'action_by' => $actionByUserId
                    ]);
                    return "La Justificación ha sido rechazada";
                }
            } else {
                throw new ModelNotFoundException('Justificación no encontrada');
            }
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Justificación no encontrada');
        } catch (\Exception $e) {
            throw new \Exception('Error al declinar la justificación.', 500);
        }
    }
    
    public function deleteJustification(int $id) {
        try {
            return $this->justificationRepository->delete($id);
        } catch (\Exception $e) {
            throw new \Exception('Error al eliminar la justificación.', 500);
        }
    }
}

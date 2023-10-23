<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function getReports(){
        $reportes_asistencia = DB::select('select * from vista_asistencia');
        $reportes_usuarios = DB::select('select * from vista_reportes_usuarios');
        $reportes_justificaciones = DB::select('select * from vista_reportes_justifications');

        return response()->json(['reportes_asistencias' => $reportes_asistencia, 
                                'reportes_usuarios' => $reportes_usuarios,
                                'reportes_justificacion' => $reportes_justificaciones]);
    }
}
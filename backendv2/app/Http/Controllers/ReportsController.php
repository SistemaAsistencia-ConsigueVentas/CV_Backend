<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function getReports(){
        $reportes_asistencia = DB::select('select * from vista_asistencia');
        $reportes_usuarios = DB::select('select * from vista_reportes_usuarios');
        $reportes_justificaciones = DB::select('select * from vista_reportes_justifications');

        $total_users = User::count();
        $usuarios_activos = User::where('status', true)->count();   
        $ingresos_mes = DB::select("select count(*) from users where DATE_PART('month', created_at) = DATE_PART('month', CURRENT_DATE) AND DATE_PART('year', created_at) = DATE_PART('year', CURRENT_DATE);");

        return response()->json(['reportes_asistencias' => $reportes_asistencia, 
                                'reportes_usuarios' => ['reporte_general' => $reportes_usuarios,
                                                        'reporte_total' => [
                                                            'total_usuarios' => $total_users,
                                                            'usuarios_activos' => $usuarios_activos,
                                                            'ingresos_mes' => $ingresos_mes,
                                                        ]],
                                'reportes_justificacion' => $reportes_justificaciones]);
    }
}
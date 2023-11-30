<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    protected $fillable = [
        'username',
        'name',
        'surname',
        'email',
        'password',
        'status',
        'status_description',
        'dni',
        'cellphone',
        'shift',
        'birthday',
        'image',
        'date_start',
        'date_end',
        'position_id'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        // Ruta base de las imágenes en la carpeta "public/photos"
        $baseUrl = config('app.url') . '/photos/' . $this->attributes['id'] . '/';

        // Nombre de archivo de la imagen almacenado en la base de datos
        $imageName = $this->attributes['image'];

        return $baseUrl . $imageName;
    }

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}


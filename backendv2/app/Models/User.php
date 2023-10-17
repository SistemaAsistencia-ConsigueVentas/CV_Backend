<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
//use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    public function getImageUrlAttribute()
    {
        // Ruta base de las imágenes en la carpeta "public/photos"
        $baseUrl = config('app.url') . '/photos/' . $this->attributes['id'] . '/';

        // Nombre de archivo de la imagen almacenado en la base de datos
        $imageName = $this->attributes['image'];

        return $baseUrl . $imageName;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function position()
    {
        return $this->hasMany(Position::class, 'id', 'position_id');
    }

    
}


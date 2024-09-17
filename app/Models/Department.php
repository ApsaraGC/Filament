<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable =[
    'name',
    ];

    public function doctors(){
        return $this->hasMany(Doctor::class);
    }
    public function patients(){
        return $this->hasMany(Patient::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

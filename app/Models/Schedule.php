<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable =[
        'doctor_id',

        'available_from',
        'available_to',
    ];
    protected $casts = [
        'available_from' => 'datetime',
        'available_to' => 'datetime',
    ];
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
     

}

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
     // Add this method to your Schedule model
//      public function getFormattedTimeAttribute()
// {
//     return $this->available_from->format('Y-m-d H:i') . ' - ' . $this->available_to->format('Y-m-d H:i');
//    //return Carbon::parse($this->available_from->format('Y-m-d H:i') . ' - ' .$this->available_to->format('Y-m-d H:i') );
// }

}

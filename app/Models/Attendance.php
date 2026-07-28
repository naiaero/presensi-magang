<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out',
        'early_leave_reason',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }}

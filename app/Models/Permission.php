<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'reason_option',
        'custom_reason',
        'proof_file',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id', 'start_date', 'end_date', 'status'];


    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    public function getplanNameAttribute()
    {
        return $this->Plan?->name;
    }

    public function getStatusTextAttribute()
    {
        if ($this->status) {
            return 'Active';
        }
        return 'Inactive';
    }

    
    public function getMemberNameAttribute()
    {
        return $this->user?->name;
    }
}

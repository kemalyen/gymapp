<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_line_1', 'address_line_2', 'city', 'country', 'post_code', 'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

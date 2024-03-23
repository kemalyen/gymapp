<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'name', 'description', 'period'];

    public function plans()
    {
        return $this->hasMany(Membership::class);
    }
}

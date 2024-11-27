<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'director',
        'year_founded',
        'team_principal',
        'logo'
    ];

    public function pilots()
    {
        return $this->hasMany(Pilot::class);
    }
}
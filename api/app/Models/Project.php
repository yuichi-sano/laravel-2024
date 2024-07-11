<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_name'
    ];

    public function scaffold()
    {
        return $this->belongsTo(Scaffold::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function totals()
    {
        return $this->hasMany(Total::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'name', 'description', 'status'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}


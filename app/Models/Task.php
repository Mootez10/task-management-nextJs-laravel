<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'due date'
    ];  

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subTasks()
    {
        return $this->hasMany(SubTask::class);
    }
}

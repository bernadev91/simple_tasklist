<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Task extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_task_id');
    }
    public function subtasks()
    {
        return $this->hasMany(self::class, 'parent_task_id');
    }

    public function getStatusName()
    {
        return ucfirst(str_replace('_', ' ' , $this->status));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Task extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'description', 'dueDate', 'status', 'workflow', 'assignee_id'];

    protected $with = ['assignee'];


    public function assignee(){
        return $this->belongsTo(User::class);
    }
}

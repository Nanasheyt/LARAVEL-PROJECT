<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Fields that are allowed to be mass-assigned (e.g. from $request->all())
    protected $fillable = [
        'task_name',
        'description',
        'status',
        'due_date',
    ];

    // Cast due_date to a Carbon date instance so it's easy to format in Blade
    protected $casts = [
        'due_date' => 'date',
    ];
}

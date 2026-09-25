<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Redirect the homepage straight to the task list
Route::redirect('/', '/tasks');

// Standard CRUD routes for tasks (index, create, store, edit, update, destroy)
Route::resource('tasks', TaskController::class);

// Extra route just for the Pending/Completed toggle button
Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

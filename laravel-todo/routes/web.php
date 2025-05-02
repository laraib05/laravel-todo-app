<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Load the main to-do app UI
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// Fetch all tasks (AJAX)
Route::get('/tasks/all', [TaskController::class, 'getAll'])->name('tasks.all');

// Add a new task
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Mark a task as completed
Route::post('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

// Delete a task
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

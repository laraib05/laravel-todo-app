<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // Display the main page
    public function index() {
        return view('todo');
    }

    // Get all tasks (both completed and non-completed)
    public function getAll() {
        return response()->json(Task::all());
    }

    // Store a new task
    public function store(Request $request) {
        // Validate the incoming data
        $request->validate([
            'title' => 'required|string|unique:tasks,title'
        ]);

        // Create a new task
        $task = Task::create(['title' => $request->title]);

        // Return the created task as a response
        return response()->json($task);
    }

    // Mark a task as completed
    public function complete($id) {
        $task = Task::findOrFail($id); // Find task by ID
        $task->completed = true;       // Set the task to completed
        $task->save();                 // Save the task

        // Return a response indicating the task is completed
        return response()->json(['status' => 'completed', 'task' => $task]);
    }

    // Delete a task
    public function destroy($id) {
        Task::destroy($id); // Delete the task by ID

        return response()->json(['status' => 'deleted']);
    }
}

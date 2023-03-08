<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task = Task::all();

        return response()->json([
            'status' => true,
            'data' => $task
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $user = User::findOrFail($request->user_id);

        if ($user->role === 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'You cannot assign a task to admin.'
            ], 401);
        } else {
            $user_id = $request->user_id;
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'not_started',
            'project_id' => $request->project_id,
            'user_id' => $user_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Task added successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response()->json([
            'status' => true,
            'data' => $task
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'project_id' => $request->project_id,
            'user_id' => $request->user_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Task updated successfully.',
            'data' => $task
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'status' => true,
            'message' => 'Task deleted successfully.'
        ], 200);
    }

    /**
     * update task status by member
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task  $task
     * @return \Illuminate\Http\Response
    */
    public function updateTaskByMember(Request $request, Task $task)
    {
        $this->validate($request, [
            'status' => [
                'required',
                Rule::in(['not_started', 'in_progress', 'ready_for_test', 'completed'])
            ]
        ]);

        // the fields other than the status are populated to avoid updating them by the team member user
        $task->update([
            'title' => $task->title,
            'status' => $request->status,
            'description' => $task->description,
            'project_id' => $task->project_id,
            'user_id' => $task->user_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Task status updated successfully.',
            'data' => $task
        ], 200);
    }
}

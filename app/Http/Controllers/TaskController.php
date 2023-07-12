<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Notifications\UnfinishedTaskNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function list()
    {
        $results = Task::with(['creator', 'subtasks'])->whereNull('parent_task_id')->orderBy('id')->get();

        return Inertia::render('TaskList', [
            'list' => TaskResource::collection($results),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:500|min:1',
        ];

        if ($request->input('id'))
        {
            $rules['id'] = 'required|integer|exists:tasks,id';
        }

        if ($request->input('parent_task_id'))
        {
            $rules['parent_task_id'] = 'required|integer|exists:tasks,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }
        else
        {
            if ($request->input('id'))
            {
                $task = Task::findOrFail((int) $request->input('id'));
            }
            else
            {
                $task = new Task();
                $task->user_id = auth()->user()->id;
            }

            $prev_status = $task->status;

            $task->title = $request->input('title');
            $task->parent_task_id = $request->input('parent_task_id') ?? NULL;
            $task->status = $request->input('status') ?? 'todo';
            $task->save();
            
            if ($prev_status != 'in_progress' && $request->input('status') == 'in_progress')
            {
                $user = auth()->user();

                $user->notify((new UnfinishedTaskNotification($task))->delay(now()->addDay()));
            }

            return response()->json(['message' => 'Task saved successfully', 'task' => new TaskResource($task)]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function details(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->subtasks()->delete();        
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}

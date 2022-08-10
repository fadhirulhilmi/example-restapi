<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Task as TaskResource;
use App\Models\Task;
use Validator;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    public function index()
    {
        $tasks = Task::all();
        return $this->handleResponse(TaskResource::collection($tasks), 'Tasks have been retrieved!');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }

        $task = Task::create($input);
        return $this->handleResponse(new TaskResource($task), 'Task created!');
    }

   
    public function show($id)
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return $this->handleError('Task not found!');
        }

        return $this->handleResponse(new TaskResource($task), 'Task retrieved.');
    }
    

    public function update(Request $request, Task $task)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }
        
        $task->description = $input['description'];
        $task->save();
        
        return $this->handleResponse(new TaskResource($task), 'Task successfully updated!');
    }
   
    public function destroy(Task $task)
    {
        $task->delete();
        return $this->handleResponse([], 'Task deleted!');
    }
}

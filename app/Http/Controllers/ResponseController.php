<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use EllipseSynergie\ApiResponse\Contracts\Response;
use App\Activity;

class TaskController extends Controller
{
    protected $respose;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function showActivity()
    {
        //Get all task
        $activity = Activity::all();
        // Return a collection of $task with pagination
        return $this->response->withPaginator($tasks, new  TaskTransformer());
    }

    public function show($id)
    {
        //Get the task
        $task = Task::find($id);
        if (!$task) {
            return $this->response->errorNotFound('Task Not Found');
        }
        // Return a single task
        return $this->response->withItem($task, new  TaskTransformer());
    }

    public function destroy($id)
    {
        //Get the task
        $task = Task::find($id);
        if (!$task) {
            return $this->response->errorNotFound('Task Not Found');
        }

        if($task->delete()) {
            return $this->response->withItem($task, new  TaskTransformer());
        } else {
            return $this->response->errorInternalError('Could not delete a task');
        }

    }

    public function store(Request $request)  {
        if ($request->isMethod('put')) {
            //Get the task
            $task = Task::find($request->task_id);
            if (!$task) {
                return $this->response->errorNotFound('Task Not Found');
            }
        } else {
            $task = new Task;
        }

        $task->id = $request->input('task_id');
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->user_id =  1; //$request->user()->id;

        if($task->save()) {
            return $this->response->withItem($task, new  TaskTransformer());
        } else {
            return $this->response->errorInternalError('Could not updated/created a task');
        }

    }

}
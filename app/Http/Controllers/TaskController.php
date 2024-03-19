<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Validated;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\TaskCollection;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\BulkStoreTaskRequest;

class TaskController extends Controller
{
    public function __construct()
    {
    $this->authorizeResource(Task::class, 'task');
    }




   public function index(Request $request){
    $tasks = QueryBuilder::for(Task::class)
    ->AllowedFilters('is_done')
    ->defaultSort('created_at')
    ->allowedSorts(['title','is_done', 'created_at'])
    ->paginate();
    return new TaskCollection($tasks);
   }

   public function show(Request $request , Task $task){
    return new TaskResource($task);
   }

   public function store(StoreTaskRequest $request){
    // dd($request->all());
    $validated = $request->validated();

    $task = Auth::user()->tasks()->create($validated);

    return new TaskResource($task);
   }


   public function update(UpdateTaskRequest $request, Task $task ){
    $validated = $request->validated();
    $task ->update($validated);
    return new TaskResource($task);
   }

   public function destroy(Request $request , Task $task){
    $task->delete();
    return response()->noContent();
   }
}

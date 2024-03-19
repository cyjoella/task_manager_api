<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\ProjectResource;
use Spatie\QueryBuilder\AllowedInclude;
use App\Http\Resources\ProjectCollection;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(Request $request){
        $project = QueryBuilder::for(Project::class)
        ->AllowedIncludes('tasks')
        ->paginate();
        return new ProjectCollection($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $project = Auth::user()->projects()->create($validated);

        foreach ($request->tasks as $taskData) {
            $task = new Task([
                'title' => $taskData['title'],
                'is_done' => $taskData['is_done'],
                'creator_id' => Auth::id(),
            ]);

            $project->tasks()->save($task);
        }

        return new ProjectResource($project);
    }

    public function show(Request $request, Project $project){
        return (new ProjectResource($project))
        ->load('tasks')
        ->load('members');
    }

    public function update(UpdateProjectRequest $request, Project $project){
        $validated = $request->validated();

        $project->update($validated);

        return new ProjectResource($project);
    }

    public function destroy(Request $request , Project $project)
    {
        $project->delete();

        return response()->noContent();
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\Http\Requests\IndexProjectRequest;
use Illuminate\Database\Eloquent\Builder;

class ProjectController extends Controller
{
    public function index(IndexProjectRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        $projects = Project::query()
            ->when(
                $filters['search'] ?? null,
                function (Builder $query, string $search) {
                    $query->where(function (Builder $query) use ($search) {
                        $query
                            ->where('client_name', 'like', "%{$search}%")
                            ->orWhere('project_name', 'like', "%{$search}%");
                    });
                },
            )
            ->when(
                $filters['status'] ?? null,
                fn (Builder $query, string $status) =>
                    $query->where('status', $status),
            )
            ->when(
                $filters['priority'] ?? null,
                fn (Builder $query, string $priority) =>
                    $query->where('priority', $priority),
            )
            ->orderBy(
                $filters['sort_by'] ?? 'created_at',
                $filters['sort_direction'] ?? 'desc',
            )
            ->paginate(10);

        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {

        $project = Project::create($request->validated());

        return (new ProjectResource($project))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    public function show(Project $project): ProjectResource
    {

        return new ProjectResource($project);

    }

    public function update(UpdateProjectRequest $request,Project $project): ProjectResource {

        $project->update($request->validated());

        return new ProjectResource($project->refresh());


    }

    public function destroy(Project $project): Response
    {

        $project->delete();

        return response()->noContent();


    }


}
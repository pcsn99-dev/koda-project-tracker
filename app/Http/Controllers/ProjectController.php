<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index(): AnonymousResourceCollection
    {

        return ProjectResource::collection(
            Project::query()
                ->latest()
                ->get()
        );


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
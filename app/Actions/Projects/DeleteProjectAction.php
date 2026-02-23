<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Services\ProjectMediaService;

class DeleteProjectAction
{
    public function __construct(private readonly ProjectMediaService $mediaService) {}

    public function __invoke(Project $project): void
    {
        $this->mediaService->deleteProjectMedia($project);
        $project->delete();
    }
}

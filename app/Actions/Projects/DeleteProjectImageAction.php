<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\ProjectMediaService;

class DeleteProjectImageAction
{
    public function __construct(private readonly ProjectMediaService $mediaService)
    {
    }

    public function __invoke(Project $project, int $imageId): void
    {
        $image = ProjectImage::query()
            ->where('project_id', $project->id)
            ->findOrFail($imageId);

        $this->mediaService->deleteImage($image);
    }
}

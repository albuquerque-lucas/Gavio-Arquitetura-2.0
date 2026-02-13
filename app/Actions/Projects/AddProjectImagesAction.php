<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Services\ProjectMediaService;

class AddProjectImagesAction
{
    public function __construct(private readonly ProjectMediaService $mediaService)
    {
    }

    /**
     * @param array<int, \Illuminate\Http\UploadedFile> $files
     */
    public function __invoke(Project $project, array $files): int
    {
        return $this->mediaService->storeGalleryImages($project, $files);
    }
}

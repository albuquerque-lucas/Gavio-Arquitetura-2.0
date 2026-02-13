<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\ProjectMediaService;
use Illuminate\Support\Facades\DB;

class BulkDeleteProjectImagesAction
{
    public function __construct(private readonly ProjectMediaService $mediaService)
    {
    }

    /**
     * @param array<int, int|string> $imageIds
     */
    public function __invoke(Project $project, array $imageIds): int
    {
        $deleted = 0;

        DB::transaction(function () use ($project, $imageIds, &$deleted) {
            ProjectImage::query()
                ->where('project_id', $project->id)
                ->whereIn('id', $imageIds)
                ->get()
                ->each(function (ProjectImage $image) use (&$deleted) {
                    $this->mediaService->deleteImage($image);
                    $deleted++;
                });
        });

        return $deleted;
    }
}

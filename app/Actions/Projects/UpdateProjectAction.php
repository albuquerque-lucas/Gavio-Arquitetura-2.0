<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Services\ProjectMediaService;
use Illuminate\Http\UploadedFile;

class UpdateProjectAction
{
    public function __construct(private readonly ProjectMediaService $mediaService) {}

    public function __invoke(Project $project, array $validated, ?UploadedFile $coverFile = null): Project
    {
        $project->update([
            'title' => $validated['title'] ?? $project->title,
            'location' => $validated['location'] ?? $project->location,
            'area' => $validated['area'] ?? $project->area,
            'category_id' => $validated['category_id'] ?? $project->category_id,
            'status' => array_key_exists('status', $validated) ? (bool) $validated['status'] : $project->status,
            'description' => $validated['description'] ?? $project->description,
            'year' => $validated['year'] ?? $project->year,
        ]);

        if ($coverFile !== null) {
            $this->mediaService->replaceCover($project, $coverFile);
        }

        return $project;
    }
}

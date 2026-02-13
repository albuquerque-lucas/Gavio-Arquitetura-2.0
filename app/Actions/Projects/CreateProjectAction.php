<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Services\ProjectMediaService;
use Illuminate\Http\UploadedFile;

class CreateProjectAction
{
    public function __construct(private readonly ProjectMediaService $mediaService)
    {
    }

    public function __invoke(array $validated, ?UploadedFile $coverFile = null): Project
    {
        $maxOrder = (int) Project::query()
            ->where('category_id', $validated['category_id'])
            ->max('order');

        $project = Project::create([
            'title' => $validated['title'],
            'location' => $validated['location'],
            'area' => $validated['area'] ?? null,
            'category_id' => $validated['category_id'],
            'status' => !empty($validated['status']),
            'description' => $validated['description'] ?? null,
            'year' => $validated['year'] ?? null,
            'order' => $maxOrder + 1,
        ]);

        if ($coverFile !== null) {
            $this->mediaService->storeCover($project, $coverFile);
        }

        return $project;
    }
}

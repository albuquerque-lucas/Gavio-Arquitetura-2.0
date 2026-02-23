<?php

namespace App\Actions\Projects;

use App\Models\Project;

class ToggleProjectCarouselAction
{
    public function __invoke(Project $project): Project
    {
        $project->status = ! $project->status;
        $project->save();

        return $project;
    }
}

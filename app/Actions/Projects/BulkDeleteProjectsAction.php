<?php

namespace App\Actions\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class BulkDeleteProjectsAction
{
    public function __construct(private readonly DeleteProjectAction $deleteProject)
    {
    }

    /**
     * @param array<int, string> $uuids
     */
    public function __invoke(array $uuids): int
    {
        $count = 0;

        DB::transaction(function () use ($uuids, &$count) {
            Project::query()
                ->whereIn('uuid', $uuids)
                ->get()
                ->each(function (Project $project) use (&$count) {
                    ($this->deleteProject)($project);
                    $count++;
                });
        });

        return $count;
    }
}

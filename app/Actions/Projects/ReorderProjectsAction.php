<?php

namespace App\Actions\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ReorderProjectsAction
{
    /**
     * @param array<int, string> $orderedUuids
     */
    public function __invoke(array $orderedUuids): void
    {
        $projects = Project::query()
            ->whereIn('uuid', $orderedUuids)
            ->get()
            ->keyBy('uuid');

        if ($projects->count() !== count($orderedUuids)) {
            throw new RuntimeException('Falha ao ordenar projetos: lista invalida.');
        }

        $baseOrder = (int) $projects->min('order');

        DB::transaction(function () use ($orderedUuids, $projects, $baseOrder) {
            foreach ($orderedUuids as $index => $uuid) {
                $project = $projects->get($uuid);
                $project->update(['order' => $baseOrder + $index]);
            }
        });
    }
}

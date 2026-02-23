<?php

namespace App\Services;

use App\Models\Cover;
use App\Models\Project;
use App\Models\ProjectImage;
use App\ProcessesImages;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProjectMediaService
{
    use ProcessesImages;

    public function storeCover(Project $project, UploadedFile $file): Cover
    {
        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $absolutePath = public_path('storage/projects/cover/'.$filename);
        $this->processImage($file, $absolutePath);

        return Cover::create([
            'path' => '/storage/projects/cover/'.$filename,
            'file_name' => $file->getClientOriginalName(),
            'project_id' => $project->id,
        ]);
    }

    public function replaceCover(Project $project, UploadedFile $file): Cover
    {
        $this->deleteCover($project);

        return $this->storeCover($project, $file);
    }

    /**
     * @param  array<int, UploadedFile>  $files
     */
    public function storeGalleryImages(Project $project, array $files): int
    {
        $created = 0;

        foreach ($files as $file) {
            $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
            $absolutePath = public_path('storage/projects/images/'.$filename);
            $this->processImage($file, $absolutePath);

            ProjectImage::create([
                'path' => '/storage/projects/images/'.$filename,
                'file_name' => $file->getClientOriginalName(),
                'project_id' => $project->id,
            ]);

            $created++;
        }

        return $created;
    }

    public function deleteCover(Project $project): void
    {
        $cover = $project->cover;
        if (! $cover) {
            return;
        }

        Storage::disk('public')->delete($this->toStoragePath($cover->path));
        $cover->delete();
    }

    public function deleteImage(ProjectImage $image): void
    {
        Storage::disk('public')->delete($this->toStoragePath($image->path));
        $image->delete();
    }

    public function deleteProjectMedia(Project $project): void
    {
        $this->deleteCover($project);

        foreach ($project->images as $image) {
            $this->deleteImage($image);
        }
    }

    private function toStoragePath(?string $publicPath): string
    {
        return ltrim(str_replace('/storage/', '', (string) $publicPath), '/');
    }
}

<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserCoverService
{
    public function store(UploadedFile $file): array
    {
        $path = $file->store('users/covers', 'public');

        return [
            'cover_path' => '/storage/' . $path,
            'cover_filename' => $file->getClientOriginalName(),
        ];
    }

    public function replace(User $user, UploadedFile $file): array
    {
        $this->delete($user);

        return $this->store($file);
    }

    public function delete(User $user): void
    {
        if (empty($user->cover_path)) {
            return;
        }

        Storage::disk('public')->delete($this->toStoragePath($user->cover_path));
    }

    private function toStoragePath(string $publicPath): string
    {
        return ltrim(str_replace('/storage/', '', $publicPath), '/');
    }
}

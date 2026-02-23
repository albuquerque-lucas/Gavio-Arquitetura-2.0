<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Services\UserCoverService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function __construct(private readonly UserCoverService $coverService) {}

    public function __invoke(User $user, array $validated, bool $ownership, ?UploadedFile $coverFile = null): User
    {
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->description = $validated['description'] ?? null;
        $user->ownership = $ownership;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($coverFile !== null) {
            $cover = $this->coverService->replace($user, $coverFile);
            $user->cover_path = $cover['cover_path'];
            $user->cover_filename = $cover['cover_filename'];
        }

        $user->save();

        return $user;
    }
}

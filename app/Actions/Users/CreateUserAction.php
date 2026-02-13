<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Services\UserCoverService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __construct(private readonly UserCoverService $coverService)
    {
    }

    public function __invoke(array $validated, bool $ownership, ?UploadedFile $coverFile = null): User
    {
        $user = new User();
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->description = $validated['description'] ?? null;
        $user->ownership = $ownership;

        if ($coverFile !== null) {
            $cover = $this->coverService->store($coverFile);
            $user->cover_path = $cover['cover_path'];
            $user->cover_filename = $cover['cover_filename'];
        }

        $user->save();

        return $user;
    }
}

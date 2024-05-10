<?php

namespace App\Policies;

use App\Models\Environment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function view(User $user): Response
    {
        return $user->role === "A"
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(User $user): Response
    {
        return $user->role === "A"
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function store(User $user): Response
    {
        return $user->role === "A"
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function edit(User $user): Response
    {
        return $user->role === "A"
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user): Response
    {
        return $user->role === "A"
            ? Response::allow()
            : Response::denyAsNotFound();
    }
    
    // For both password and information
    public function updateMe(User $user): Response
    {
        return $user->user_id === auth()->user()->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user): bool
    {
        return $user->role === "A";
        //return $user->user_id !== $User->user_id && $user->role === "A";
    }
}

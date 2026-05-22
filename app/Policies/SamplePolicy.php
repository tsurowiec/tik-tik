<?php

namespace App\Policies;

use App\Models\Sample;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SamplePolicy
{
    public function before(User $user, $ability): bool
    {
        return true;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Sample $sample): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Sample $sample): bool
    {
        return false;
    }

    public function delete(User $user, Sample $sample): bool
    {
        return false;
    }

    public function restore(User $user, Sample $sample): bool
    {
        return false;
    }

    public function forceDelete(User $user, Sample $sample): bool
    {
        return false;
    }
}

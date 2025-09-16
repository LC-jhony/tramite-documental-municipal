<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Employe;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Employe');
    }

    public function view(AuthUser $authUser, Employe $employe): bool
    {
        return $authUser->can('View:Employe');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Employe');
    }

    public function update(AuthUser $authUser, Employe $employe): bool
    {
        return $authUser->can('Update:Employe');
    }

    public function delete(AuthUser $authUser, Employe $employe): bool
    {
        return $authUser->can('Delete:Employe');
    }

    public function restore(AuthUser $authUser, Employe $employe): bool
    {
        return $authUser->can('Restore:Employe');
    }

    public function forceDelete(AuthUser $authUser, Employe $employe): bool
    {
        return $authUser->can('ForceDelete:Employe');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Employe');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Employe');
    }

    public function replicate(AuthUser $authUser, Employe $employe): bool
    {
        return $authUser->can('Replicate:Employe');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Employe');
    }

}
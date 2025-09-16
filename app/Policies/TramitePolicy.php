<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tramite;
use Illuminate\Auth\Access\HandlesAuthorization;

class TramitePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tramite');
    }

    public function view(AuthUser $authUser, Tramite $tramite): bool
    {
        return $authUser->can('View:Tramite');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tramite');
    }

    public function update(AuthUser $authUser, Tramite $tramite): bool
    {
        return $authUser->can('Update:Tramite');
    }

    public function delete(AuthUser $authUser, Tramite $tramite): bool
    {
        return $authUser->can('Delete:Tramite');
    }

    public function restore(AuthUser $authUser, Tramite $tramite): bool
    {
        return $authUser->can('Restore:Tramite');
    }

    public function forceDelete(AuthUser $authUser, Tramite $tramite): bool
    {
        return $authUser->can('ForceDelete:Tramite');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tramite');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tramite');
    }

    public function replicate(AuthUser $authUser, Tramite $tramite): bool
    {
        return $authUser->can('Replicate:Tramite');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tramite');
    }

}
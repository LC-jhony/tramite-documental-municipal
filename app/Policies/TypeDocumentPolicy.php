<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TypeDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypeDocumentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TypeDocument');
    }

    public function view(AuthUser $authUser, TypeDocument $typeDocument): bool
    {
        return $authUser->can('View:TypeDocument');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TypeDocument');
    }

    public function update(AuthUser $authUser, TypeDocument $typeDocument): bool
    {
        return $authUser->can('Update:TypeDocument');
    }

    public function delete(AuthUser $authUser, TypeDocument $typeDocument): bool
    {
        return $authUser->can('Delete:TypeDocument');
    }

    public function restore(AuthUser $authUser, TypeDocument $typeDocument): bool
    {
        return $authUser->can('Restore:TypeDocument');
    }

    public function forceDelete(AuthUser $authUser, TypeDocument $typeDocument): bool
    {
        return $authUser->can('ForceDelete:TypeDocument');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TypeDocument');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TypeDocument');
    }

    public function replicate(AuthUser $authUser, TypeDocument $typeDocument): bool
    {
        return $authUser->can('Replicate:TypeDocument');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TypeDocument');
    }

}
<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for 'super_administrador' role
    * |--------------------------------------------------------------------------
    * | Super administrator can do everything
    */

    /**
     * Determine whether the user can see topics related to the 'super_administrador' role.
     */
    public function viewSuperAdminTopics(User $user, User $model): bool
    {
        return $model->hasRole('super_administrador');
    }

    /*
    * Determine whether the user can save topics related to the 'super_administrador' role.
    */
    public function saveSuperAdminTopics(User $user, User $model): bool
    {
        return $model->hasRole('super_administrador');
    }

    /*
    * Determine whether the user can update topics related to the 'super_administrador' role.
    */
    public function updateSuperAdminTopics(User $user, User $model): bool
    {
        return $model->hasRole('super_administrador');
    }

    /*
    * Determine whether the user can delete topics related to the 'super_administrador' role.
    */
    public function deleteSuperAdminTopics(User $user, User $model): bool
    {
        return $model->hasRole('super_administrador');
    }


    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for 'administrador' role
    * |--------------------------------------------------------------------------
    * | Administrators can do everything except for the super administrator
    */

    /**
     * Determine whether the user can see topics related to the 'administrador' role.
     */
    public function viewAdminTopics(User $user, User $model): bool
    {
        return $model->hasRole('administrador') || $model->hasRole('super_administrador');
    }

    /*
    * Determine whether the user can save topics related to the 'administrador' role.
    */
    public function saveAdminTopics(User $user, User $model): bool
    {
        return $model->hasRole('administrador') || $model->hasRole('super_administrador');
    }

    /*
    * Determine whether the user can update topics related to the 'administrador' role.
    */
    public function updateAdminTopics(User $user, User $model): bool
    {
        return $model->hasRole('administrador') || $model->hasRole('super_administrador');
    }

    /*
    * Determine whether the user can delete topics related to the 'administrador' role.
    */
    public function deleteAdminTopics(User $user, User $model): bool
    {
        return $model->hasRole('administrador') || $model->hasRole('super_administrador');
    }


    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for 'vendedor' role
    * |--------------------------------------------------------------------------
    * | Vendors can do everything except for the super administrator and administrator
    */

    /**
     * Determine whether the user can see topics related to the 'vendedor' role.
     */
    public function viewVendorTopics(User $user, User $model): bool
    {
        return $model->hasRole('vendedor') || $model->hasRole('super_administrador') || $model->hasRole('administrador');
    }

    /*
    * Determine whether the user can save topics related to the 'vendedor' role.
    */
    public function saveVendorTopics(User $user, User $model): bool
    {
        return $model->hasRole('vendedor') || $model->hasRole('super_administrador') || $model->hasRole('administrador');
    }

    /*
    * Determine whether the user can update topics related to the 'vendedor' role.
    */
    public function updateVendorTopics(User $user, User $model): bool
    {
        return $model->hasRole('vendedor') || $model->hasRole('super_administrador') || $model->hasRole('administrador');
    }

    /*
    * Determine whether the user can delete topics related to the 'vendedor' role.
    */
    public function deleteVendorTopics(User $user, User $model): bool
    {
        return $model->hasRole('vendedor') || $model->hasRole('super_administrador') || $model->hasRole('administrador');
    }


    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for 'miembro' role
    * |--------------------------------------------------------------------------
    * | Members can do everything except for the super administrator, administrator and vendor
    */

    /**
     * Determine whether the user can see topics related to the 'miembro' role.
     */
    public function viewMemberTopics(User $user, User $model): bool
    {
        return $model->hasRole('miembro') || $model->hasRole('super_administrador') || $model->hasRole('administrador') || $model->hasRole('vendedor');
    }

}

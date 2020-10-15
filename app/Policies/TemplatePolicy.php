<?php

namespace App\Policies;

use App\Models\Template;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any templates.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view the template.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function view(User $user, Template $template)
    {
        return $template->user_id == $user->id;
    }

    /**
     * Determine whether the user can create templates.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the template.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function update(User $user, Template $template)
    {
        return $template->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the template.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function delete(User $user, Template $template)
    {
        return $template->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the template.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function restore(User $user, Template $template)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the template.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Template  $template
     * @return mixed
     */
    public function forceDelete(User $user, Template $template)
    {
        //
    }
}

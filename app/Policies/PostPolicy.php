<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Post $post): bool
    {
        if ($post->is_published) {
            return true;
        }

        // Only author or admin can preview unpublished post
        return $user !== null && ($user->isAdmin() || $user->id === $post->user_id);
    }

    /**
     * Determine whether the user can create models.
     * Only Admin and Editor can create posts.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can update the model.
     * - Admin can edit ANY post.
     * - Editor can ONLY edit their OWN posts.
     * - Regular User is denied.
     */
    public function update(User $user, Post $post): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        if ($user->isEditor() && $user->id === $post->user_id) {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki hak akses untuk mengedit artikel ini. Hanya admin atau penulis artikel asli yang diizinkan.');
    }

    /**
     * Determine whether the user can delete the model.
     * - Strictly Admin only, or Editor can only delete their own.
     * Let's allow Admin, or Editor if they own it.
     */
    public function delete(User $user, Post $post): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        if ($user->isEditor() && $user->id === $post->user_id) {
            return Response::allow();
        }

        return Response::deny('Hanya Administrator atau pembuat artikel yang berhak menghapus artikel ini.');
    }
}

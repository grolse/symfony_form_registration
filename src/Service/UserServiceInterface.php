<?php


namespace App\Service;

use App\Entity\User;

/**
 * Interface UserServiceInterface
 * @package App\Service
 *
 * @author Oleh Hrynko <oleh.hrynko@aengizer.dev>
 */
interface UserServiceInterface
{
    /**
     * Create a new user.
     *
     * @param User $user
     * @return User
     */
    public function create(User $user): User;

    /**
     * Change user's isActive flag.
     *
     * @param User $user
     * @param bool $active
     */
    public function changeStatus(User $user, bool $active): void;

    /**
     * Get user by ID.
     *
     * @param int $userId
     * @return User
     */
    public function getUserById(int $userId): User;
}

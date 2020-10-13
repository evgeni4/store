<?php


namespace App\Service\Users;


use App\Entity\User;

interface UserServiceInterface
{
    public function findEmail(string $email): ?User;

    public function save(User $user): bool;

    public function edit(User $user): bool;

    public function currentUser(): ?User;

}
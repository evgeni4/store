<?php


namespace App\Service\Users;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EncryptionServiceInterface\ArgonEncryption;
use App\Service\EncryptionServiceInterface\EncryptionServiceInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Security\Core\Security;

class UserService implements UserServiceInterface
{
    private $security;
    private $userRepository;
    private $encryptService;

    public function __construct(Security $security, UserRepository $userRepository, ArgonEncryption $encryptService)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->encryptService = $encryptService;
    }

    public function findEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function save(User $user): bool
    {
        $countUser = count($this->all());
        $this->checkUserRole($countUser, $user);
//        $passwordHash = $this->encryptService->hash($user->getPassword());
//        $user->setPassword($passwordHash);
        var_dump($user->getPassword());
        return $this->userRepository->insert($user);
    }

    public function currentUser(): ?User
    {
        return $this->security->getUser();
    }

    public function all(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param int $countUser
     * @param User $user
     */
    public function checkUserRole(int $countUser, User $user): void
    {
        switch ($countUser) {
            case "0":
                $user->setRoles(['ROLE_SUPER_ADMIN']);
                break;
            case "1":
                $user->setRoles(['ROLE_ADMIN']);
                break;
            default:
                $user->setRoles(['ROLE_USER']);
                break;
        }
    }

    public function edit(User $user): bool
    {
        $user->setCountry($user->getState()->getCountry());
        return $this->userRepository->update($user);
    }

    public function updatePassword(User $user, $password): bool
    {
        $passwordHash = $this->encryptService->hash($password);
        $user->setPassword($passwordHash);
        return $this->userRepository->update($user);
    }

    public function customerFindAll()
    {
        $customer = $this->userRepository->findAll();
        $count = 0;
        foreach ($customer as $key => $item) {
            foreach ($item->getRoles() as $role) {
                if ($role=='ROLE_USER') {
                    $count++;
                }
            }
        }
        return $count;
    }
}
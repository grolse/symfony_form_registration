<?php


namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService implements UserServiceInterface
{
    /** @var \Doctrine\Persistence\ObjectRepository  */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $passwordEncoder;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    /**
     * Create a new user.
     *
     * @param User $user
     * @return User
     * @throws UserAlreadyExistsException
     */
    public function create(User $user): User
    {
        /** @var User $existedUser */
        $existedUser = $this->userRepository->findOneByUsername($user->getUsername());
        if ($existedUser) {
            throw new UserAlreadyExistsException('User already exists');
        }
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Change user's isActive flag.
     *
     * @param User $user
     * @param bool $active
     */
    public function changeStatus(User $user, bool $active): void
    {
        $user->setIsActive($active);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Get user by ID.
     *
     * @param int $userId
     * @return User
     * @throws UserNotFoundException
     */
    public function getUserById(int $userId): User
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new UserNotFoundException('User with ID: '.$userId. ' not found');
        }
        return $user;
    }
}

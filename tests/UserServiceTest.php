<?php

namespace App\Tests;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserServiceTest extends WebTestCase
{
    const TEST_PASWORD = 'testtest';

    private $userService;

    public function setUp()
    {
        self::bootKernel();
        $this->userService = self::$container->get(UserServiceInterface::class);

    }

    public function testGetUserId()
    {
        $user = $this->userService->getUserById(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('testuser', $user->getUsername());
    }

    public function testCreateUser()
    {
        $user = new User();
        $user->setUsername('testtest1')
            ->setPassword(self::TEST_PASWORD);

        $newUser = $this->userService->create($user);

        $this->assertNotNull($newUser->getId());
        $this->assertNotEquals(self::TEST_PASWORD, $newUser->getPassword());
        $this->assertEquals('testtest1', $newUser->getUsername());
    }

    public function testGetUserIdFailed()
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID: 100500 not found');
        $this->userService->getUserById(100500);
    }
}

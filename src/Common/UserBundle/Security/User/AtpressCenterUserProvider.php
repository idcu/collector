<?php
namespace Common\UserBundle\Security\User;

use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use FOS\UserBundle\Model\UserManagerInterface;
use Common\ConstantBundle\Utility\Center;
use Common\UserBundle\Exception\UninitializedUserException;

class AtpressCenterUserProvider implements UserProviderInterface{

    protected $userManager;
    protected $center;

    public function __construct(UserManagerInterface $userManager, Center $center)
    {
        $this->userManager = $userManager;
        $this->center = $center;
    }

    public function loadUserByUsername($username)
    {
        if ($username == null)
            throw new UsernameNotFoundException(sprintf('No user was found.'));
        $user = $this->userManager->findUserByUsername($username);
        if (!$user) {
            $centerData = $this->center->loadUser($username);
            if (!$centerData) {
                throw new UsernameNotFoundException(sprintf('No user with name "%s" was found.', $username));
            }
            $user = $this->userManager->createUser();
            $this->center->setUser($user,$centerData);
//            throw new UninitializedUserException('User account need initialize.',$centerData);
        }
        else
        {
            $this->center->fetchCenterData($user);
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = $this->userManager->getClass();
        if (!$user instanceof $class) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === $this->userManager->getClass();
    }
}
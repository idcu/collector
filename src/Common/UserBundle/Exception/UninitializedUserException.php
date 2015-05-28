<?php
namespace Common\UserBundle\Exception;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UninitializedUserException extends AuthenticationException{
}
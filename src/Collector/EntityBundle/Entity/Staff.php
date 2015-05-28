<?php
namespace Collector\EntityBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Common\FormBundle\Validator as MyAssert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Collector\EntityBundle\Entity\Staff
 *
 * @Assert\Callback(methods={"plainPasswordValidate"}, groups={"admin"})
 *
 * @ORM\Table(name = "staff")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="id",message="このIDは既に使用されています",groups={"admin"})
 */
class Staff extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @MyAssert\NotBlank(message="パスワードが未入力です", groups={"admin"})
     */
    protected $plainPassword;

    /**
     * @MyAssert\NotBlank(message="姓が未入力です", groups={"admin"})
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    protected $lastName;

    /**
     * @MyAssert\NotBlank(message="名が未入力です", groups={"admin"})
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $signature;

    /**
     * @var string
     * @MyAssert\NotBlank(message="メールアドレス未入力です", groups={"admin"})
     * @Assert\Email(
     *     message = "メールアドレスが不正です",
     *     checkMX = true,
     *     groups={"admin"})
     */
    protected $email;


    public function __construct() {
        parent::__construct();
    }


    /**
     * パスワード、全て同じ文字の場合はエラー
     * @param ExcecutionContext $context
     */
    public function plainPasswordValidate(ExecutionContext $context)
    {
        $aaa = $this->getPlainpassword();
        if ( preg_match('/^(.)\1+$/', $aaa) ) {
            $propertyPath = $context->getPropertyPath() . '.plainPassword';
            $context->setPropertyPath($propertyPath);
            $context->addViolation('同じ文字の連続ではないものを入力してください', array(), null);
            return;
        }
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getName(){
        return $this->getLastName()." ".$this->getFirstName();
    }

    /**
     * Set signature
     *
     * @param string $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }


    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }
}
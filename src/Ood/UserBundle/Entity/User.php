<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user_user")
 * @ORM\Entity(repositoryClass="Ood\UserBundle\Repository\UserRepository")
 *
 * @UniqueEntity("username",
 *      message="user.username.unique_entity"
 * )
 */
class User
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * Contains the ID of the user
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id_user",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the user"
     *     }
     * )
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idUser;

    /**
     * Contains the nickname of the person
     *
     * @var string $nickname
     *
     * @ORM\Column(
     *     name="nickname",
     *     type="string",
     *     length=80,
     *     nullable=true,
     *     options={"comment"="Contains the nickname of the person"}
     * )
     *
     * @Assert\Length(
     *     max=80,
     *     maxMessage="user.nickname.max_length"
     * )
     */
    protected $nickname;

    /**
     * Contains the firstname of the person
     *
     * @var string $firstname
     *
     * @ORM\Column(
     *     name="firstname",
     *     type="string",
     *     length=80,
     *     nullable=true,
     *     options={"comment"="Contains the firstname of the person"}
     * )
     *
     * @Assert\Length(
     *     max=80,
     *     maxMessage="user.firstname.max_length"
     * )
     */
    protected $firstname;

    /**
     * Contains the lastname of the person
     *
     * @var string $lastname
     *
     * @ORM\Column(
     *     name="lastname",
     *     type="string",
     *     length=80,
     *     nullable=true,
     *     options={"comment"="Contains the lastname of the person"}
     * )
     *
     * @Assert\Length(
     *     max=80,
     *     maxMessage="user.lastname.max_length"
     * )
     */
    protected $lastname;

    /**
     * Contains the username (alias) of the person
     *
     * @var string
     *
     * @ORM\Column(
     *     name="username",
     *     type="string",
     *     length=20,
     *     nullable=false,
     *     options={"comment"="Contains the username (alias) of the person"}
     * )
     *
     * @Assert\NotBlank(
     *     message="user.username.not_blank"
     * )
     *
     * @Assert\Length(
     *     max=20,
     *     maxMessage="user.username.max_length"
     * )
     */
    protected $username;


    /**
     * Contains the email address of the person
     *
     * @var string
     *
     * @ORM\Column(
     *     name="email",
     *     type="string",
     *     length=255,
     *     nullable=false,
     *     options={"comment":"Contains the email address of the person"}
     * )
     *
     * @Assert\NotBlank(
     *     message="user.email.no_blank"
     * )
     *
     * @Assert\Length(
     *     max=255,
     *     maxMessage="user.email.max_length"
     * )
     */
    protected $email;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     *
     * @ORM\Column(
     *     name="password",
     *     type="string",
     *     length=255,
     *     nullable=true,
     *     options={"comment"="Encrypted password. Must be persisted."}
     * )
     *
     * @Assert\Length(
     *     max=255,
     *     maxMessage="user.password.max_length"
     * )
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     *
     * @ORM\Column(
     *     name="plain_password",
     *     type="string",
     *     length=255,
     *     nullable=true,
     *     options={"comment"="Plain password. Used for model validation. Must not be persisted."}
     * )
     *
     * @Assert\Length(
     *     max=255,
     *     maxMessage="user.password.max_length"
     * )
     */
    protected $plainPassword;

    /**
     * Date of registration
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="registered_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Date of registration"}
     * )
     *
     * @Assert\DateTime(
     *     message="user.registeredAt.not_validate"
     * )
     */
    protected $registeredAt;

    /**
     * Last update date
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="update_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Date of registration"}
     * )
     *
     * @Assert\DateTime(
     *     message="user.updatedAt.not_validate"
     * )
     */
    protected $updateAt;

    /**
     * user is locked ?
     *
     * @var boolean
     *
     * @ORM\Column (
     *     name="locked",
     *     type="boolean",
     *     nullable=false,
     *     options={"comment"="user is locked",
     *              "default"="0"
     *              }
     *     )
     */
    protected $locked;

    /**
     * Role of the user
     *
     * @var string
     *
     * @ORM\Column(
     *      name="role",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *      options={"comment"="Role of the user"}
     * )
     *
     * @Assert\Length(
     *     max=255,
     *     maxMessage="user.role.max_length"
     * )
     */
    protected $role;

    /** *******************************
     *  CONSTRUCT
     */

    public function __construct()
    {
        $dateAt = new \DateTime();
        $this
            ->setRegisteredAt($dateAt)
            ->setUpdateAt($dateAt)
            ->setLocked(false);;
    }

    /** *******************************
     *  GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     *
     * @return User
     */
    public function setNickname(string $nickname): User
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname(string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt(): \DateTime
    {
        return $this->registeredAt;
    }

    /**
     * @param \DateTime $registeredAt
     *
     * @return User
     */
    public function setRegisteredAt(\DateTime $registeredAt): User
    {
        $this->registeredAt = $registeredAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt(): \DateTime
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     *
     * @return User
     */
    public function setUpdateAt(\DateTime $updateAt): User
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     *
     * @return User
     */
    public function setLocked(bool $locked): User
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function setRole(string $role): User
    {
        $this->role = $role;
        return $this;
    }
}

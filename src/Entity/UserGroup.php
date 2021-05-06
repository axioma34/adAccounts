<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 */
class UserGroup
{
    const EDIT = 'EDIT';


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $systemId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\UsersGroups", mappedBy="user")
//     */
//    protected $users;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSystemId(): ?int
    {
        return $this->systemId;
    }

    public function setSystemId(?int $systemId): self
    {
        $this->systemId = $systemId;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }
}

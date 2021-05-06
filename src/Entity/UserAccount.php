<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserAccountRepository")
 */
class UserAccount
{
    /**
     * @var User
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="accounts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var AdAccount
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\AdAccount", inversedBy="users")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserAccount
     */
    public function setUser(User $user): UserAccount
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return AdAccount
     */
    public function getAccount(): AdAccount
    {
        return $this->account;
    }

    /**
     * @param AdAccount $account
     * @return UserAccount
     */
    public function setAccount(AdAccount $account): UserAccount
    {
        $this->account = $account;
        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UsersGroupsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersGroupsRepository::class)
 */
class UsersGroups
{
    /**
     * @var User
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var UserGroup
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\UserGroup", inversedBy="users")
     * @ORM\JoinColumn(name="user_group_id", referencedColumnName="id")
     */
    protected $group;

    /**
     * @var AdAccount
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\AdAccount")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
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
     * @return UsersGroups
     */
    public function setUser(User $user): UsersGroups
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return UserGroup
     */
    public function getGroup(): UserGroup
    {
        return $this->group;
    }

    /**
     * @param UserGroup $group
     * @return UsersGroups
     */
    public function setGroup(UserGroup $group): UsersGroups
    {
        $this->group = $group;
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
     * @return UsersGroups
     */
    public function setAccount(AdAccount $account): UsersGroups
    {
        $this->account = $account;
        return $this;
    }

}

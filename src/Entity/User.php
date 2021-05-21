<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    private $roles = [];

    /**
     *
     * @Ignore()
     *
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    /**
     * @var ArrayCollection
     *
     * @Ignore()
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserAccount", mappedBy="user",
     *      cascade={"persist", "remove"}, orphanRemoval=true)
     *
     */
    protected $accounts;

    /**
     * @var ArrayCollection
     *
     * @Ignore()
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UsersGroups", mappedBy="user",
     *      cascade={"persist", "remove"}, orphanRemoval=true)
     *
     */
    protected $groups;

     /**
      *
      * @Ignore()
      *
      * @ORM\Column(type="string", unique=true, nullable=true)
      */
     private $apiToken;

    /**
     * @Ignore()
     */
     private $salt;

    /**
     * @Ignore()
     */
    private $username;

    #[Pure] public function __construct() {
        $this->accounts = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return User
     */
    public function setActive(bool $active): User
    {
        $this->active = $active;
        return $this;
    }

    public function addAccount(AdAccount $account): User
    {
        $this->accounts[] = (new UserAccount())
            ->setAccount($account)
            ->setUser($this);

        return $this;
    }

    public function removeAccount (AdAccount $account) {
        $accounts = $this->accounts->filter(function ($userAccount) use ($account) {
            return $userAccount->getAccount()->getId() == $account->getId();
        });

        foreach ($accounts as $account) {
            $this->accounts->removeElement($account);
        }
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @return Collection
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    /**
     * @return null|string
     */
    public function getApiToken() : ?string
    {
        return $this->apiToken;
    }

    /**
     * @param string $apiToken
     * @return User
     */
    public function setApiToken(string $apiToken): User
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    /**
     * @param ArrayCollection $accounts
     * @return User
     */
    public function setAccounts(ArrayCollection $accounts): User
    {
        $this->accounts = $accounts;
        return $this;
    }
}

<?php

namespace TheFeed\Business\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use TheFeed\Storage\SQL\doctrine\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'leboncoin__users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    private ?string $firstname = null;

    #[ORM\Column(length: 30)]
    private ?string $lastname = null;

    #[ORM\Column(length: 10)]
    private ?string $tel = null;

    #[ORM\ManyToMany(targetEntity: Announcement::class, inversedBy: 'usersliked')]
    private Collection $likedAnnouncements;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Announcement::class, orphanRemoval: true)]
    private Collection $announcements;

    public function __construct()
    {
        $this->announcements = new ArrayCollection();
        $this->likedAnnouncements = new ArrayCollection();
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
     * @return string|null
     */
    public function getTel(): ?string
    {
        return $this->tel;
    }

    /**
     * @param string|null $tel
     */
    public function setTel(?string $tel): User
    {
        $this->tel = $tel;
        return $this;
    }


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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getLikedAnnouncements(): ArrayCollection|Collection
    {
        return $this->likedAnnouncements;
    }


    public function addLikedAnnouncement(Announcement $announcement): self
    {
        if (!$this->likedAnnouncements->contains($announcement)) {
            $this->likedAnnouncements->add($announcement);
            $announcement->addUsersliked($this);
        }

        return $this;
    }

    public function removeLikedAnnouncement(Announcement $announcement): self
    {
        if ($this->likedAnnouncements->removeElement($announcement)) {
            // set the owning side to null (unless already changed)
            if ($announcement->getUsersLiked()->contains($this)) {
                $announcement->removeUsersliked($this);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getAnnouncements(): ArrayCollection|Collection
    {
        return $this->announcements;
    }

    public function addAnnounecemnts(Announcement $announcement): self {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements->add($announcement);
            $announcement->setAuthor($this);
        }
        return $this;
    }

    public function removedAnnounecemnts(Announcement $announcement): self {
        if ($this->announcements->removeElement($announcement)) {
            $announcement->setAuthor(null);
        }
        return $this;
    }


}
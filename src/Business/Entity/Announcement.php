<?php

namespace TheFeed\Business\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use TheFeed\Storage\SQL\doctrine\AnnouncementRepository;

#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
#[ORM\Table(name: 'leboncoin__announcements')]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $name = null;

    #[ORM\Column(length: 2083, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 2083, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 180)]
    private ?string $adress = null;

    #[ORM\Column(length: 30)]
    private ?string $city = null;

    #[ORM\Column]
    private ?int $postalcode = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'likedAnnouncements')]
    private Collection $usersliked;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
        $this->usersliked = new ArrayCollection();
    }


    /**
     * @param int|null $id
     */
    public function setId(?int $id): Announcement
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): Announcement
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $picture
     */
    public function setPicture(?string $picture): Announcement
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): Announcement
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string|null $adress
     */
    public function setAdress(?string $adress): Announcement
    {
        $this->adress = $adress;
        return $this;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): Announcement
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param int|null $postalcode
     */
    public function setPostalcode(?int $postalcode): Announcement
    {
        $this->postalcode = $postalcode;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getAdress(): ?string
    {
        return $this->adress;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return int|null
     */
    public function getPostalcode(): ?int
    {
        return $this->postalcode;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState(?string $state): Announcement
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getUsersliked(): ArrayCollection|Collection
    {
        return $this->usersliked;
    }

    public function addUserLiked(User $user): self {
        if (!$this->usersliked->contains($user)) {
            $this->usersliked->add($user);
        }
        return $this;
    }

    public function removeUserLiked(User $user): self {
        $this->usersliked->removeElement($user);
        return $this;
    }


}
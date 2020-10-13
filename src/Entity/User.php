<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @UniqueEntity(fields={"phone"}, message="There is already an account with this phone")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "This ( First name ) should not be blank.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z\s]+$/",
     *     match=true,
     *     message="Your ( First name ) Name cannot contain a number"
     * )
     */
    private $firstName;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "This ( Last name ) should not be blank.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z\s]+$/",
     *     match=true,
     *     message="Your ( Last name ) cannot contain a number"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "This ( Email ) should not be blank.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(message = "This ( Phone ) should not be blank.")
     * @Assert\Length(min="6",minMessage="Your Phone must be at least {{ limit }} characters long")
     * @Assert\Length(max="20",maxMessage="Your phone must contain no more than {{ limit }} characters.")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=true,
     *     message="Your ( Phone ) cannot contain a word"
     * )
     */
    private $phone;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "This ( Address ) should not be blank.")
     */
    private $address;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "This ( Postcode ) should not be blank.")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=true,
     *     message="Your ( Postcode ) cannot contain a word"
     * )
     */
    private $postCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;
    /**
     * @ORM\OneToMany (targetEntity="App\Entity\Categories", mappedBy="user")
     */
    private $categories;

    /**
     * @ORM\Column(type="boolean")
     */
    private $banned = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "This ( City Town ) should not be blank.")
     */
    private $cityTown;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="user")
     * @Assert\NotBlank(message = "This ( Country ) should not be blank.")

     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=States::class, inversedBy="user")
     * @Assert\NotBlank(message = "This ( State ) should not be blank.")
     */
    private $state;
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->createDate=new \DateTime('now');
    }
    public function __toString(){

        return $this->getEmail();
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
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';
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
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostCode(): ?int
    {
        return $this->postCode;
    }

    public function setPostCode(int $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setUser($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getUser() === $this) {
                $category->setUser(null);
            }
        }

        return $this;
    }

    public function getBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }
    public function getCityTown(): ?string
    {
        return $this->cityTown;
    }

    public function setCityTown(string $cityTown): self
    {
        $this->cityTown = $cityTown;

        return $this;
    }

    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    public function setCountry(?Countries $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getState(): ?States
    {
        return $this->state;
    }

    public function setState(?States $state): self
    {
        $this->state = $state;

        return $this;
    }

}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use DH\Auditor\Provider\Doctrine\Auditing\Annotation as Audit;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(indexes={
 *      @ORM\Index(name="created_index", columns={"created"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @Audit\Auditable()
 * @Audit\Security(view={"ROLE_ADMIN"})
 */
class User implements UserInterface, \Serializable
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const ALL_ROLES = ['ROLE_USER', 'ROLE_LOCATION_MODERATOR', 'ROLE_LOCATION_ADMIN', 'ROLE_ADMIN'];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Audit\Ignore
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=127, unique=false, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=127, unique=false, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $newEmail;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $newEmailToken;

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $location;


    /**
     * @ORM\Column(type="boolean", options={"default":0}, nullable=false)
     */
    private $enabled = false;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="owner")
     */
    private $categories;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastPassResetRequest;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $passResetToken;




    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $ipAddressDuringRegistration;


    public function __construct()
    {
        $this->enabled = false;
        $this->categories = new ArrayCollection();
    }

    public function toArray()
    {
        $arr = [
            'id' => $this->getId(),
            'text' => $this->getEmail(),
            'email' => $this->getEmail(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'roles' => $this->getRoles()
        ];
        return $arr;
    }

    public function __toString()
    {
        $niceName = '';
        if ($this->getFirstName()) $niceName .= $this->getFirstName();
        if ($this->getLastName()) $niceName .= ' ' . $this->getLastName();
        if ($niceName) {
            $niceName .= ' (' . $this->getEmail() . ')';
            return $niceName;
        }
        return $this->getEmail();
    }



    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }


    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->enabled
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->enabled
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }


    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getName(): ?string
    {
        return $this->__toString();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {

        $roles = $this->roles;


        //$roles = array_values($roles);
        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Checks if the user has the selected role
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        return in_array($role, $this->getRoles());
    }

    public function getRolesAsCommaSeparatedString()
    {
        $rolesArr = $this->getRoles();
        return implode(',', $rolesArr);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNewEmail(): ?string
    {
        return $this->newEmail;
    }

    public function setNewEmail(?string $newEmail): self
    {
        $this->newEmail = $newEmail;

        return $this;
    }

    public function getNewEmailToken(): ?string
    {
        return $this->newEmailToken;
    }

    public function setNewEmailToken(?string $newEmailToken): self
    {
        $this->newEmailToken = $newEmailToken;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getLastPassResetRequest(): ?\DateTimeInterface
    {
        return $this->lastPassResetRequest;
    }

    public function setLastPassResetRequest(?\DateTimeInterface $lastPassResetRequest): self
    {
        $this->lastPassResetRequest = $lastPassResetRequest;

        return $this;
    }

    public function getPassResetToken(): ?string
    {
        return $this->passResetToken;
    }

    public function setPassResetToken(?string $passResetToken): self
    {
        $this->passResetToken = $passResetToken;

        return $this;
    }

    public function getIpAddressDuringRegistration(): ?string
    {
        return $this->ipAddressDuringRegistration;
    }

    public function setIpAddressDuringRegistration(?string $ipAddressDuringRegistration): self
    {
        $this->ipAddressDuringRegistration = $ipAddressDuringRegistration;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setOwner($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getOwner() === $this) {
                $category->setOwner(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }




}

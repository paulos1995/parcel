<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DH\Auditor\Provider\Doctrine\Auditing\Annotation as Audit;
use App\Entity\ScanPlan;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(indexes={
 *      @ORM\Index(name="created_index", columns={"created"})
 * })
 * @Audit\Auditable()
 * @Audit\Security(view={"ROLE_ADMIN"})
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commaSeparatedEmails;

    /**
     * @ORM\ManyToOne(targetEntity="ScanPlan")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $scanPlan;

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="categories")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="Parcel", mappedBy="category")
     */
    private $parcels;

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

    public function __construct()
    {
        $this->parcels = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }


    public function toArray()
    {
        $arr = [
            'id' => $this->getId(),
            'text' => $this->getName().' '.($this->getScan()?'📷':'🔒'),
            'name' => $this->getName(),
            'scan' => $this->getScan(),

        ];
        if ($this->getLocation()) {
            $arr['locationId'] =$this->getLocation()->getId();
            $arr['locationName'] =$this->getLocation()->getName();
        } else {
            $arr['locationId'] = null;
            $arr['locationName'] = '';
        }
        return $arr;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getScan(): ?bool
    {
        if (!$this->getScanPlan()) return false;
        return $this->getScanPlan()->getScan();
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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

    /**
     * @return Collection|Parcel[]
     */
    public function getParcels(): Collection
    {
        return $this->parcels;
    }

    public function addParcel(Parcel $letter): self
    {
        if (!$this->parcels->contains($letter)) {
            $this->parcels[] = $letter;
            $letter->setCategory($this);
        }

        return $this;
    }

    public function removeParcel(Parcel $letter): self
    {
        if ($this->parcels->removeElement($letter)) {
            // set the owning side to null (unless already changed)
            if ($letter->getCategory() === $this) {
                $letter->setCategory(null);
            }
        }

        return $this;
    }

    public function getCommaSeparatedEmails(): ?string
    {
        return $this->commaSeparatedEmails;
    }

    public function setCommaSeparatedEmails(?string $commaSeparatedEmails): self
    {
        $this->commaSeparatedEmails = $commaSeparatedEmails;

        return $this;
    }

    public function getScanPlan(): ?ScanPlan
    {
        return $this->scanPlan;
    }

    public function setScanPlan(?ScanPlan $scanPlan): self
    {
        $this->scanPlan = $scanPlan;

        return $this;
    }


}

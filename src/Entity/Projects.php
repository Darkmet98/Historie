<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Projects
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProjectsRepository")
 * @ORM\Table(name="projects")
 */
class Projects
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=60, nullable=true, options={"default"="NULL"})
     */
    private $description = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Icon", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $icon = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="Repository", type="text", length=65535, nullable=false)
     */
    private $repository;

    /**
     * @var string
     *
     * @ORM\Column(name="Branch", type="string", length=50, nullable=false)
     */
    private $branch;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Pofile", inversedBy="projectid")
     * @ORM\JoinTable(name="projects_has_pofiles",
     *   joinColumns={
     *     @ORM\JoinColumn(name="ProjectId", referencedColumnName="Id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="PoFileId", referencedColumnName="Id")
     *   }
     * )
     */
    private $pofileid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Releases", inversedBy="projectid")
     * @ORM\JoinTable(name="projects_has_releases",
     *   joinColumns={
     *     @ORM\JoinColumn(name="ProjectId", referencedColumnName="Id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ReleasesId", referencedColumnName="Id")
     *   }
     * )
     */
    private $releasesid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="projectid")
     * @ORM\JoinTable(name="projects_has_users",
     *   joinColumns={
     *     @ORM\JoinColumn(name="ProjectId", referencedColumnName="Id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="UserId", referencedColumnName="id")
     *   }
     * )
     */
    private $userid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pofileid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->releasesid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userid = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getRepository(): ?string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * @return Collection|Pofile[]
     */
    public function getPofileid(): Collection
    {
        return $this->pofileid;
    }

    public function addPofileid(Pofile $pofileid): self
    {
        if (!$this->pofileid->contains($pofileid)) {
            $this->pofileid[] = $pofileid;
        }

        return $this;
    }

    public function removePofileid(Pofile $pofileid): self
    {
        if ($this->pofileid->contains($pofileid)) {
            $this->pofileid->removeElement($pofileid);
        }

        return $this;
    }

    /**
     * @return Collection|Releases[]
     */
    public function getReleasesid(): Collection
    {
        return $this->releasesid;
    }

    public function addReleasesid(Releases $releasesid): self
    {
        if (!$this->releasesid->contains($releasesid)) {
            $this->releasesid[] = $releasesid;
        }

        return $this;
    }

    public function removeReleasesid(Releases $releasesid): self
    {
        if ($this->releasesid->contains($releasesid)) {
            $this->releasesid->removeElement($releasesid);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserid(): Collection
    {
        return $this->userid;
    }

    public function addUserid(User $userid): self
    {
        if (!$this->userid->contains($userid)) {
            $this->userid[] = $userid;
        }

        return $this;
    }

    public function removeUserid(User $userid): self
    {
        if ($this->userid->contains($userid)) {
            $this->userid->removeElement($userid);
        }

        return $this;
    }


    public function __toString()
    {
        return $this->name;
    }
}

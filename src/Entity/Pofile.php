<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pofile
 *
 * @ORM\Table(name="pofile")
 * @ORM\Entity
 */
class Pofile
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
     * @ORM\Column(name="Name", type="string", length=60, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Path", type="text", length=65535, nullable=false)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="Entries", type="text", length=0, nullable=false)
     */
    private $entries;

    /**
     * @var int
     *
     * @ORM\Column(name="Position", type="integer", nullable=false)
     */
    private $position;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Projects", mappedBy="pofileid")
     */
    private $projectid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projectid = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getEntries(): ?string
    {
        return $this->entries;
    }

    public function setEntries(string $entries): self
    {
        $this->entries = $entries;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|Projects[]
     */
    public function getProjectid(): Collection
    {
        return $this->projectid;
    }

    public function addProjectid(Projects $projectid): self
    {
        if (!$this->projectid->contains($projectid)) {
            $this->projectid[] = $projectid;
            $projectid->addPofileid($this);
        }

        return $this;
    }

    public function removeProjectid(Projects $projectid): self
    {
        if ($this->projectid->contains($projectid)) {
            $this->projectid->removeElement($projectid);
            $projectid->removePofileid($this);
        }

        return $this;
    }

}

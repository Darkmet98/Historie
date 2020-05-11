<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Releases
 *
 * @ORM\Table(name="releases")
 * @ORM\Entity
 */
class Releases
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
     * @ORM\Column(name="Version", type="string", length=30, nullable=false)
     */
    private $version;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Changelog", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $changelog = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="File", type="text", length=65535, nullable=false)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="Md5", type="text", length=65535, nullable=false)
     */
    private $md5;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Projects", mappedBy="releasesid")
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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getChangelog(): ?string
    {
        return $this->changelog;
    }

    public function setChangelog(?string $changelog): self
    {
        $this->changelog = $changelog;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getMd5(): ?string
    {
        return $this->md5;
    }

    public function setMd5(string $md5): self
    {
        $this->md5 = $md5;

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
            $projectid->addReleasesid($this);
        }

        return $this;
    }

    public function removeProjectid(Projects $projectid): self
    {
        if ($this->projectid->contains($projectid)) {
            $this->projectid->removeElement($projectid);
            $projectid->removeReleasesid($this);
        }

        return $this;
    }

}

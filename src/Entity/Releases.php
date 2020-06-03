<?php

namespace App\Entity;

use App\Repository\ReleasesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReleasesRepository::class)
 */
class Releases
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Version;

    /**
     * @ORM\Column(type="text")
     */
    private $Changelog;

    /**
     * @ORM\Column(type="text")
     */
    private $File;

    /**
     * @ORM\Column(type="text")
     */
    private $Md5;

    /**
     * @ORM\ManyToOne(targetEntity=Projects::class, inversedBy="releases")
     */
    private $Project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->Version;
    }

    public function setVersion(string $Version): self
    {
        $this->Version = $Version;

        return $this;
    }

    public function getChangelog(): ?string
    {
        return $this->Changelog;
    }

    public function setChangelog(string $Changelog): self
    {
        $this->Changelog = $Changelog;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->File;
    }

    public function setFile(string $File): self
    {
        $this->File = $File;

        return $this;
    }

    public function getMd5(): ?string
    {
        return $this->Md5;
    }

    public function setMd5(string $Md5): self
    {
        $this->Md5 = $Md5;

        return $this;
    }

    public function getProject(): ?Projects
    {
        return $this->Project;
    }

    public function setProject(?Projects $Project): self
    {
        $this->Project = $Project;

        return $this;
    }

    public function __toString()
    {
        return $this->Version;
    }
}

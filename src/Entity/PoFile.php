<?php

namespace App\Entity;

use App\Repository\PoFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PoFileRepository::class)
 */
class PoFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $Name;

    /**
     * @ORM\Column(type="text")
     */
    private $Path;

    /**
     * @ORM\Column(type="json")
     */
    private $Entries = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $Position;

    /**
     * @ORM\ManyToOne(targetEntity=Projects::class, inversedBy="poFiles")
     */
    private $Project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->Path;
    }

    public function setPath(string $Path): self
    {
        $this->Path = $Path;

        return $this;
    }

    public function getEntries(): ?array
    {
        return $this->Entries;
    }

    public function setEntries(array $Entries): self
    {
        $this->Entries = $Entries;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->Position;
    }

    public function setPosition(int $Position): self
    {
        $this->Position = $Position;

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
        return $this->Name;
    }
}

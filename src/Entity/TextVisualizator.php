<?php

namespace App\Entity;

use App\Repository\TextVisualizatorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=TextVisualizatorRepository::class)
 * @Vich\Uploadable
 */
class TextVisualizator
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $Background;

    /**
     * @Vich\UploadableField(mapping="TextVisualizators", fileNameProperty="Background")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="integer")
     */
    private $FontSize;

    /**
     * @ORM\Column(type="integer")
     */
    private $LineHeight;

    /**
     * @ORM\Column(type="integer")
     */
    private $TopPosition;

    /**
     * @ORM\Column(type="integer")
     */
    private $LeftPosition;

    /**
     * @ORM\OneToMany(targetEntity=Projects::class, mappedBy="Visualizator")
     */
    private $projects;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Name;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBackground(): ?string
    {
        return $this->Background;
    }

    public function setBackground(string $Background): self
    {
        $this->Background = $Background;

        return $this;
    }

    public function getFontSize(): ?int
    {
        return $this->FontSize;
    }

    public function setFontSize(int $FontSize): self
    {
        $this->FontSize = $FontSize;

        return $this;
    }

    public function getLineHeight(): ?int
    {
        return $this->LineHeight;
    }

    public function setLineHeight(int $LineHeight): self
    {
        $this->LineHeight = $LineHeight;

        return $this;
    }

    public function getTopPosition(): ?int
    {
        return $this->TopPosition;
    }

    public function setTopPosition(int $TopPosition): self
    {
        $this->TopPosition = $TopPosition;

        return $this;
    }

    public function getLeftPosition(): ?int
    {
        return $this->LeftPosition;
    }

    public function setLeftPosition(int $LeftPosition): self
    {
        $this->LeftPosition = $LeftPosition;

        return $this;
    }

    public function setProject(?Projects $Project): self
    {
        $this->Project = $Project;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->Background = $image->getFilename();
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function __toString()
    {
        return $this->Name;
    }

    /**
     * @return Collection|Projects[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Projects $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setVisualizator($this);
        }

        return $this;
    }

    public function removeProject(Projects $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getVisualizator() === $this) {
                $project->setVisualizator(null);
            }
        }

        return $this;
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
}

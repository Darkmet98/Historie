<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProjectsRepository::class)
 * @Vich\Uploadable
 */
class Projects
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
    private $Name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Icon;

    /**
     * @Vich\UploadableField(mapping="Projects", fileNameProperty="Icon")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $repository;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Branch;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="projects")
     */
    private $Users;

    /**
     * @ORM\OneToMany(targetEntity=Releases::class, mappedBy="Project")
     */
    private $releases;

    /**
     * @ORM\OneToMany(targetEntity=PoFile::class, mappedBy="Project")
     */
    private $poFiles;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
        $this->releases = new ArrayCollection();
        $this->poFiles = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->Icon;
    }

    public function setIcon(?string $Icon): self
    {
        $this->Icon = $Icon;

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
        return $this->Branch;
    }

    public function setBranch(string $Branch): self
    {
        $this->Branch = $Branch;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(User $user): self
    {
        if (!$this->Users->contains($user)) {
            $this->Users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->Users->contains($user)) {
            $this->Users->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Releases[]
     */
    public function getReleases(): Collection
    {
        return $this->releases;
    }

    public function addRelease(Releases $release): self
    {
        if (!$this->releases->contains($release)) {
            $this->releases[] = $release;
            $release->setProject($this);
        }

        return $this;
    }

    public function removeRelease(Releases $release): self
    {
        if ($this->releases->contains($release)) {
            $this->releases->removeElement($release);
            // set the owning side to null (unless already changed)
            if ($release->getProject() === $this) {
                $release->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PoFile[]
     */
    public function getPoFiles(): Collection
    {
        return $this->poFiles;
    }

    public function addPoFile(PoFile $poFile): self
    {
        if (!$this->poFiles->contains($poFile)) {
            $this->poFiles[] = $poFile;
            $poFile->setProject($this);
        }

        return $this;
    }

    public function removePoFile(PoFile $poFile): self
    {
        if ($this->poFiles->contains($poFile)) {
            $this->poFiles->removeElement($poFile);
            // set the owning side to null (unless already changed)
            if ($poFile->getProject() === $this) {
                $poFile->setProject(null);
            }
        }

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
            $this->Icon = $image->getFilename();
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
}

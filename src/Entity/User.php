<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deleted_at', timeAware: false, hardDelete: true)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private $updated_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $deleted_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $first_login_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $last_login_at;

    #[ORM\OneToMany(mappedBy: 'manager', targetEntity: UserTeam::class)]
    private $managed_teams;

    #[ORM\ManyToOne(targetEntity: UserJob::class, inversedBy: 'users')]
    private $job;

    #[ORM\ManyToMany(targetEntity: UserTeam::class, inversedBy: 'users')]
    private $teams;

    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: Project::class)]
    private $created_projects;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'users')]
    private $projects;

    public function __construct()
    {
        $this->managed_teams = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->created_projects = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFirstname();
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function getFirstLoginAt(): ?\DateTimeImmutable
    {
        return $this->first_login_at;
    }

    public function setFirstLoginAt(?\DateTimeImmutable $first_login_at): self
    {
        $this->first_login_at = $first_login_at;

        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeImmutable
    {
        return $this->last_login_at;
    }

    public function setLastLoginAt(?\DateTimeImmutable $last_login_at): self
    {
        $this->last_login_at = $last_login_at;

        return $this;
    }

    /**
     * @return Collection<int, UserTeam>
     */
    public function getManagedTeams(): Collection
    {
        return $this->managed_teams;
    }

    public function addManagedTeam(UserTeam $managedTeam): self
    {
        if (!$this->managed_teams->contains($managedTeam)) {
            $this->managed_teams[] = $managedTeam;
            $managedTeam->setManager($this);
        }

        return $this;
    }

    public function removeManagedTeam(UserTeam $managedTeam): self
    {
        if ($this->managed_teams->removeElement($managedTeam)) {
            // set the owning side to null (unless already changed)
            if ($managedTeam->getManager() === $this) {
                $managedTeam->setManager(null);
            }
        }

        return $this;
    }

    public function getJob(): ?UserJob
    {
        return $this->job;
    }

    public function setJob(?UserJob $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Collection<int, UserTeam>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(UserTeam $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
        }

        return $this;
    }

    public function removeTeam(UserTeam $team): self
    {
        $this->teams->removeElement($team);

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getCreatedProjects(): Collection
    {
        return $this->created_projects;
    }

    public function addCreatedProject(Project $createdProject): self
    {
        if (!$this->created_projects->contains($createdProject)) {
            $this->created_projects[] = $createdProject;
            $createdProject->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedProject(Project $createdProject): self
    {
        if ($this->created_projects->removeElement($createdProject)) {
            // set the owning side to null (unless already changed)
            if ($createdProject->getCreatedBy() === $this) {
                $createdProject->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        $this->projects->removeElement($project);

        return $this;
    }
}

<?php

namespace Stewie\WikiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stewie\UserBundle\Entity\Group;
use Stewie\UserBundle\Entity\User;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Stewie\WikiBundle\Repository\SpaceRepository")
 * @ORM\Table(name="stewie_wiki_space")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable(logEntryClass="Stewie\WikiBundle\Entity\WikiLogEntry")
 * @Vich\Uploadable
 */
class Space
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=180, unique=false)
     */
    private $name;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="Stewie\WikiBundle\Entity\Page", mappedBy="space")
     */
    private $page;

    /**
     * @ORM\ManyToMany(targetEntity="Stewie\UserBundle\Entity\User")
     * @ORM\JoinTable(name="stewie_wiki_space_user",
     *      joinColumns={@ORM\JoinColumn(name="space_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="Stewie\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="stewie_wiki_space_group",
     *      joinColumns={@ORM\JoinColumn(name="space_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    private $group;

    public function __construct()
    {
        // $this->roles = new ArrayCollection();

        $this->page = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->group = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
     * @return Collection|Page[]
     */
    public function getPage(): Collection
    {
        return $this->page;
    }

    public function addPage(Page $page): self
    {
        if (!$this->page->contains($page)) {
            $this->page[] = $page;
            $page->setSpace($this);
        }

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->page->contains($page)) {
            $this->page->removeElement($page);
            // set the owning side to null (unless already changed)
            if ($page->getSpace() === $this) {
                $page->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroup(): Collection
    {
        return $this->group;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->group->contains($group)) {
            $this->group[] = $group;
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        $this->group->removeElement($group);

        return $this;
    }

}

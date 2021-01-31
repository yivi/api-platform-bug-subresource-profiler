<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=BookTag::class, inversedBy="books")
     * @ApiSubresource
     */
    private $bookTags;

    public function __construct()
    {
        $this->bookTags = new ArrayCollection();
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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|BookTag[]
     */
    public function getBookTags(): Collection
    {
        return $this->bookTags;
    }

    public function addBookTag(BookTag $bookTag): self
    {
        if ( ! $this->bookTags->contains($bookTag)) {
            $this->bookTags[] = $bookTag;
            $bookTag->addBook($this);
        }

        return $this;
    }

    public function removeBookTag(BookTag $bookTag): self
    {
        if ($this->bookTags->removeElement($bookTag)) {
            $bookTag->removeBook($this);
        }

        return $this;
    }
}

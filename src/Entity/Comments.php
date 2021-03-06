<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Table comment de la BDD de Groupomania
 * 
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Content::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE") 
     */
    private $Content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", length=2000)
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?Content
    {
        return $this->Content;
    }

    public function setContent(?Content $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function checkMessage(string $message)
    {
        if(preg_match("/^[^&'\{\[\<\>\]\}#=@$\s]{1}[^&\{\[\<\>\]\}#=@$]{9,1990}+$/", $message)) {
            return true;
        }
        return false;       
    }
}

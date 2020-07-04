<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContentRepository::class)
 */
class Content
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $topic;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Likes::class, mappedBy="Content")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE") 
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="Content")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contents")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE") 
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaPathUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaPathFile;

    private $file;


    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Likes[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Likes $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setContent($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getContent() === $this) {
                $like->setContent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setContent($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getContent() === $this) {
                $comment->setContent(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUsername(): ?User
    {
        return $this->username;
    }

    public function setUsername(?User $username): self
    {
        $this->username = $username;

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

    public function getMediaPathUrl(): ?string
    {
        return $this->mediaPathUrl;
    }

    public function setMediaPathUrl(?string $mediaPathUrl): self
    {
        $this->mediaPathUrl = $mediaPathUrl;

        return $this;
    }

    public function getMediaPathFile(): ?string
    {
        return $this->mediaPathFile;
    }

    public function setMediaPathFile(?string $mediaPathFile): self
    {
        $this->mediaPathFile = $mediaPathFile;

        return $this;
    }

    public function setFiles(UploadedFile $file = null)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function uploadMediaFile(String $media = null,$type)
    { 
        $path = date('His')."_".$this->file->getClientOriginalName();
        $this->file->move($this->getUploadRootDir($type),$path);
        return $path;
    }

    public function deleteFile(String $path, String $type) { 
        $deleted = true; 
        if(file_exists($this->getUploadRootDir($type).$path)) {
            $deleted = unlink($this->getUploadRootDir($type).$path);
        }
        return $deleted;
    }

    protected function getPath(String $mediaPathFile) {
        $arrayMediaPathFile = explode("/",$mediaPathFile);
        return $arrayMediaPathFile[count($arrayMediaPathFile)-1];
    }

    protected function getUploadRootDir(String $type) {
        if($type=="Image") {
            return __DIR__.'/../../public/uploadFile/images/';
        } else {
            return __DIR__.'/../../public/uploadFile/musics/';   
        }
    }
}

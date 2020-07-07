<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     errorPath="username",
 *     message="Le pseudo que vous avez choisi existe déjà!"
 * )
 * @UniqueEntity(
 *     fields={ "email"},
 *     errorPath="email",
 *     message="Cette adresse email est déjà utilisée!"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\Regex("/^[a-zA-Z0-9_.]{3,25}$/i",
     * message="Le format du login est invalide ! Il doit comporter entre 3 et 25 caractères. La ponctuation (sauf le .), les espaces et les caractères spéciaux sont exclus.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "L'adresse email '{{ value }}' n'est pas une adresse valide."
     * )
     */
    private $email;

   /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{9,}$/",
     * message="Le mot de passe doit comporter au moins 10 caractères, posséder au moins un chiffre, une lettre majuscule et minuscule et l'un des caractères spéciaux suivants: @$!%*?&.")
     */
    private $password;

    /**
     * @Assert\EqualTo(
     *     propertyPath="password", message="Vous n'avez pas saisi le même mot de passe!"
     * )
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $subscribeAt;

     /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Content::class, mappedBy="username", orphanRemoval=true)
     */
    private $contents;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSubscribeAt(): ?\DateTimeInterface
    {
        return $this->subscribeAt;
    }

    public function setSubscribeAt(\DateTimeInterface $subscribeAt): self
    {
        $this->subscribeAt = $subscribeAt;

        return $this;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    public function setRolesUser(Array $role) {
        $this->roles = $role;
        return $this;
    }

    public function getRoles(): array {
        return $this->roles;
    }

    /**
     * @return Collection|Content[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(Content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setUsername($this);
        }

        return $this;
    }

    public function removeContent(Content $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            // set the owning side to null (unless already changed)
            if ($content->getUsername() === $this) {
                $content->setUsername(null);
            }
        }

        return $this;
    }
}

<?php


namespace Sthom\Back\Entity;

class User
{
    private ?int $id = null;
    private ?string $email = null;
    private ?string $nom = null;
    private ?string $prenom = null;
    private ?string $roles = null;
    private ?string $mdp = null;
    public final function getId(): ?int
    {
        return $this->id;
    }

    public final function getEmail(): ?string
    {
        return $this->email;
    }

    public final function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public final function getNom(): ?string
    {
        return $this->nom;
    }

    public final function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public final function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public final function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public final function getRoles(): ?string
    {
        return $this->roles;
    }

    public final function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    public final function getMdp(): ?string
    {
        return $this->mdp;
    }

    public final function setMdp(string $mdp): void
    {
        $this->mdp = $mdp;
    }




}
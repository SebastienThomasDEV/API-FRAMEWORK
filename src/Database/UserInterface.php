<?php

namespace Sthom\Back\Database;

/**
 * Cette interface permet de définir les méthodes que doit implémenter une classe
 * qui représente un utilisateur dans notre application
 *
 * @package Sthom\Back\Database
 */
interface UserInterface
{

    /**
     * Cette méthode permet de récupérer l'identifiant de l'utilisateur
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Cette méthode permet de récupérer le rôle de l'utilisateur
     * @return string|null
     */
    public function getRole(): ?string;


    /**
     * Cette méthode permet de définir le rôle de l'utilisateur
     * @param string $role
     * @return self
     */
    public function setRole(string $role): self;

    /**
     * Cette méthode permet de récupérer le mot de passe de l'utilisateur
     * @return string|null
     */
    public function getPassword(): ?string;

    /**
     * Cette méthode permet de définir le mot de passe de l'utilisateur
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self;

}
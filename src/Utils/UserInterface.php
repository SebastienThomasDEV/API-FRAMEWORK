<?php

namespace Sthom\Back\Utils;

interface UserInterface
{


    /*
     * Get the user identifier
     */
    public function getIdentifier(): mixed;

    /*
     * Get the user roles
     */
    public function getRole(): string;

    /*
     * Set the user roles
     */
    public function setRoles(string $roles): void;


    public function getPassword(): string;

    public function setPassword(string $password): void;

}
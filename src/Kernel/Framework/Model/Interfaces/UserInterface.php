<?php

namespace Sthom\Back\Kernel\Framework\Model\Interfaces;

interface UserInterface
{


    /*
     * Get the user identifier
     */
    public function getIdentifier(): mixed;

    /*
     * Get the user roles
     */
    public function getRoles(): string;

    /*
     * Set the user roles
     */
    public function setRoles(string $roles): void;




}
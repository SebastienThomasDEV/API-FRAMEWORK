<?php

namespace Sthom\Back\Kernel\Framework\Utils;

interface EntityInterface
{

    /*
 * Get object as array
 */
    public function toArray(): array;

    /*
     * Get the user id
     */
    public function getId(): ?int;




}
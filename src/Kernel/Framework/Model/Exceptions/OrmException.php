<?php

namespace Sthom\Back\Kernel\Framework\Model\Exceptions;

class OrmException extends \Exception
{
    public function __construct($message = "ORM Error", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

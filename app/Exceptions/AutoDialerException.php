<?php

namespace App\Exceptions;


class AutoDialerException extends \Exception {


    public $message;
    /**
     * @param string $message
     */
    function __construct($message)
    {
        $this->message = $message;
    }
}
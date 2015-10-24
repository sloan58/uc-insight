<?php
/**
 * Created by PhpStorm.
 * User: sloan58
 * Date: 10/24/15
 * Time: 12:00 AM
 */

namespace App\Exceptions;


class SoapException extends \Exception {


    public $message;
    /**
     * @param string $message
     */
    function __construct($message)
    {
        $this->message = $message;
    }
}
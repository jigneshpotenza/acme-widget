<?php
/**
 * Exception handeling class to handel exception
 *
 * @package Acme Widget Co
 */
namespace App\Exceptions;

use Exception;

/**
 * CustomException class.
 */
class CustomException extends Exception
{
    /**
	 * Constructor for the CustomException class. Loads exception message.
	 */
    public function __construct($message = "Something went wrong", $code = 0)
    {
        parent::__construct($message, $code);
    }
}

<?php

namespace AppBundle\Exception;

class UsernameExistsException extends \Exception
{
    const MESSAGE = 'Username already exists';
}

<?php

namespace AppBundle\Transfer;

class RegistrationTransfer
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var array
     */
    private $password;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return array
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $password
     */
    public function setPassword(array $password)
    {
        $this->password = $password;
    }
}

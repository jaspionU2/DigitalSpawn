<?php

declare(strict_types=1);

namespace App\Schemas;

use Respect\Validation\Validator as v;

class UserSchema extends Schema
{
    protected $name;

    protected $lastname;

    protected $password;

    protected $email;

    protected $telephone;

    public function __construct()
    {
        $this->name = v::stringType()
            ->length(3, 100)
            ->notEmpty()
            ->setName('name')
        ;

        $this->lastname = v::stringType()
            ->length(3, 100)
            ->notEmpty()
            ->setName('lastname')
        ;

        $this->password = v::stringType()
            ->notEmpty()
            ->length(8, null)
            ->regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/')
            ->setName('password')
        ;

        $this->email = v::stringType()
            ->email()
            ->notEmpty()
            ->setName('email')
        ;

        $this->telephone = v::stringType()
            ->notEmpty()
            ->length(11, 11)
            ->regex('/^[1-9][0-9]9[0-9]{8}$/')
            ->setName('telephone');
    }
}

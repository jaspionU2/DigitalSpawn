<?php

namespace App\Models\UserModel;

use DateTime;
use Doctrine\DBAL\Schema\DefaultExpression\CurrentTimestamp;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity]
#[ORM\Table(name: 'user')]
class UserModel
{
    #[ORM\Id]
    #[ORM\Column(name: 'user_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue()]
    private ?int $id = null;

    #[ORM\Column(name: 'user_name', type: Types::STRING)]
    private string $name;

    #[ORM\Column(name: 'user_email', type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Column(name: 'user_password', type: Types::STRING)]
    private string $password_hash;
    
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => new CurrentTimestamp()], insertable: false, updatable: false)]
    private DateTime $created_at;

    public function getId() : int|null
    {
        return $this->id;
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function getPasswordHash() : string|null
    {
        return $this->password_hash;
    }

    public function getCreatedAt(): DateTime|null
    {
        return $this->created_at;
    }


    public function setName(string $name) : string|null
    {
       return $this->name = $name;
    }

    public function setEmail(string $email) : string|null
    {
        return $this->email = $email;
    }
}

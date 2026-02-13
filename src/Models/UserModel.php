<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeImmutable;
use Doctrine\DBAL\Schema\DefaultExpression\CurrentTimestamp;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class UserModel extends BaseModel
{
    protected array $fillable = ['name', 'lastname', 'email', 'password', 'telephone'];

    protected array $hidden = ['password_hash', 'created_at'];

    #[ORM\Id]
    #[ORM\Column(name: 'user_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue()]
    protected ?int $id = null;

    #[ORM\Column(name: 'user_name', type: Types::STRING)]
    protected string $name;

    #[ORM\Column(name: 'user_lastname', type: Types::STRING)]
    protected string $lastname;

    #[ORM\Column(name: 'user_email', type: Types::STRING, unique: true)]
    protected string $email;

    #[ORM\Column(name: 'user_password', type: Types::STRING)]
    protected string $password;

    #[ORM\Column(name: 'user_telephone', type: Types::STRING)]
    protected string $telephone;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => new CurrentTimestamp()], insertable: false, updatable: false)]
    protected DateTimeImmutable $created_at;

    #[ORM\Column(name: 'email_verified', type: Types::BOOLEAN, options: ['default' => false])]
    protected bool $emailVerified = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $email_verified): void
    {
        $this->emailVerified = $email_verified;
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Schema\DefaultExpression\CurrentTimestamp;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'email_token')]
class EmailTokenModel extends BaseModel
{
    protected array $fillable = ['token'];

    #[ORM\Id]
    #[ORM\Column(name: 'token_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue()]
    protected int $token_id;

    #[ORM\Column(name: 'token', type: Types::STRING, unique: true)]
    protected string $token;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => new CurrentTimestamp()], insertable: false, updatable: false)]
    protected DateTimeImmutable $created_at;

    #[ORM\Column(name: 'is_used', type: Types::BOOLEAN, options: ['default' => false])]
    protected bool $isUsed = false;

    public function getTokenId(): int
    {
        return $this->token_id;
    }

    public function setTokenId(int $token_id): self
    {
        $this->token_id = $token_id;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): self
    {
        $this->isUsed = $isUsed;

        return $this;
    }
}
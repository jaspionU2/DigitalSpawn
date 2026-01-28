<?php declare(strict_types=1);

namespace App\Models;

use DateTime;
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

    #[ORM\Column(name: 'timestamp', type: Types::DATETIME_MUTABLE, options: ['default' => new CurrentTimestamp()], insertable: false, updatable: false)]
    protected DateTime $timestamp;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}

<?php declare(strict_types=1);

namespace App\Models;

use DateTime;
use Doctrine\DBAL\Schema\DefaultExpression\CurrentTimestamp;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
#[ORM\Entity]
#[ORM\Table(name: 'user')]
class UserModel extends Model
{
    protected array $fillable = ['name', 'email', 'password_hash'];

    #[ORM\Id]
    #[ORM\Column(name: 'user_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue()]
    protected ?int $id = null;

    #[ORM\Column(name: 'user_name', type: Types::STRING)]
    protected string $name;

    #[ORM\Column(name: 'user_email', type: Types::STRING, unique: true)]
    protected string $email;

    #[ORM\Column(name: 'user_password', type: Types::STRING)]
    protected string $password_hash;
    
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => new CurrentTimestamp()], insertable: false, updatable: false)]
    protected DateTime $created_at;

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


    public function setName(string $name) : void
    {
       $this->name = $name;
    }

    public function setEmail(string $email) : void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password_hash = $password;
    }
}

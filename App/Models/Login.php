<?php
namespace App\Models;

use App\Core\Model;

class Login extends Model
{
    protected null|int $id = null;
    protected string $login;
    protected string $password;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public static function getByLogin(string $login) : ?Login
    {
        //login is unique, should never return more than 1
        $logins = self::getAll('login=?', [$login]);
        if (empty($logins))
        {
            return null;
        }
        else
        {
            return $logins[0];
        }
    }
}
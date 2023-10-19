<?php

namespace App\Models;
use App\Core\Model;
use DateTime;

class Login extends Model
{
    protected string $username;
    protected DateTime $lastAction;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getLastAction(): DateTime
    {
        return $this->lastAction;
    }

    public function setLastAction(DateTime $lastAction): void
    {
        $this->lastAction = $lastAction;
    }

    public static function OneByName($login) : Login|null {
        $logged = Login::getAll('login like ?', [$login]);
        if (count($logged) < 1) {
            return null;
        }
        return $logged[0];
    }

}
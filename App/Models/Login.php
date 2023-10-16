<?php

namespace App\Models;
use App\Core\Model;
use DateTime;

class Login extends Model
{
    protected string $login;
    protected DateTime $lastAction;

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
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
        $logged = Login::getAll('login like ?' [$login]);
        if (count($logged) < 1) {
            return null;
        }
        return $logged[0];
    }

}
<?php

namespace App\Models;

use App\Core\Model;

class Runner extends Model
{
    protected null|int $id = null;
    protected int $logins_id;
    protected int $personalDetails_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLoginsId(): int
    {
        return $this->logins_id;
    }

    public function setLoginsId(int $logins_id): void
    {
        $this->logins_id = $logins_id;
    }

    public function getPersonalDetailsId(): int
    {
        return $this->personalDetails_id;
    }

    public function setPersonalDetailsId(int $personalDetails_id): void
    {
        $this->personalDetails_id = $personalDetails_id;
    }

}
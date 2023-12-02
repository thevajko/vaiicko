<?php

namespace App\Models;

use App\Core\Model;
use DateTime;

class PersonalDetail extends Model
{
    protected null|int $id = null;
    protected string $email;
    protected string $name;
    protected string $surname;
    protected string $gender;
    protected string $birthDate;
    protected string $street;
    protected string $city;
    protected string $postalCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getBirthDate(): DateTime
    {
        // internally is last_action presented as string, because of DB
        return new DateTime($this->birthDate);
    }

    public function setBirthDate(DateTime $birthDate): void
    {
        // converting to string presentation of timedate, so ORM can store data to DB
        $this->birthDate = $birthDate->format('Y-m-d H:i:s');
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }


}
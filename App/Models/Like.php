<?php

namespace App\Models;

use Framework\Core\Model;

class Like extends Model
{
    protected ?int $id = null;
    protected int $post_id;
    protected string $liker;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function setPostId(int $post_id): void
    {
        $this->post_id = $post_id;
    }

    public function getLiker(): string
    {
        return $this->liker;
    }

    public function setLiker(string $liker): void
    {
        $this->liker = $liker;
    }
}

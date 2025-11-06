<?php

namespace App\Models;

use Framework\Core\Model;

class Post extends Model
{

    protected ?int $id = null;
    protected ?string $text;
    protected ?string $picture;
    protected ?string $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * Get number of likes for this post
     *
     * @return int
     * @throws \Exception
     */
    public function getLikeCount(): int
    {
        // get all likes of this post
        return Like::getCount("post_id = ?", [$this->id]);
    }

    /**
     * Toggle like from given user
     *
     * @param string $userName
     * @throws \Exception
     */
    public function likeToggle($userName): void
    {
        // only for stored
        if (empty($this->id)) {
            throw new \Exception("Post must be stored or loaded to toggle like.");
        }
        // check if there is already a like from this user
        $likes = Like::getAll("post_id = ? AND liker like ?", [$this->id, $userName]);
        if (count($likes) > 0) {
            // remove likes if there are any
            foreach ($likes as $like) {
                $like->delete();
            }
        } else {
            // add like if there was none
            $like = new Like();
            $like->setPostId($this->id);
            $like->setLiker($userName);
            $like->save();
        }
    }
}
<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

class Post
{
    private int $id;

    private Type $type;

    private Status $status;

    private string $title;

    private string $content = '';

    private string $name;

    private int $authorId;

    private int $parentForumId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Post
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): Post
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): Post
    {
        $this->status = $status;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Post
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Post
    {
        $this->content = $content;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Post
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): Post
    {
        $this->authorId = $authorId;

        return $this;
    }

    public function getParentForumId(): int
    {
        if (Type::Topic === $this->type) {
            return $this->parentForumId;
        }

        throw new \LogicException('Only Topics can have Parent Forum ID.');
    }

    public function setParentForumId(int $parentForumId): Post
    {
        if (Type::Topic === $this->type) {
            $this->parentForumId = $parentForumId;

            return $this;
        }

        throw new \LogicException('Only Topics can have Parent Forum ID.');
    }
}

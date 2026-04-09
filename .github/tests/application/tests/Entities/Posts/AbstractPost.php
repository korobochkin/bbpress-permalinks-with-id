<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\PostInterface;

abstract class AbstractPost implements PostInterface
{
    private int $id;

    private Status $status;

    private string $title;

    private string $content = '';

    private string $name;

    /**
     * @var positive-int
     */
    private int $authorId;

    /**
     * @var non-empty-string
     */
    private string $samplePermalink;

    private \DateTime $postDate;

    #[\Override]
    public function getId(): int
    {
        return $this->id;
    }

    #[\Override]
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    #[\Override]
    abstract public function getType(): Type;

    #[\Override]
    public function getStatus(): Status
    {
        return $this->status;
    }

    #[\Override]
    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    #[\Override]
    public function getTitle(): string
    {
        return $this->title;
    }

    #[\Override]
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    #[\Override]
    public function getContent(): string
    {
        return $this->content;
    }

    #[\Override]
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    #[\Override]
    public function getName(): string
    {
        return $this->name;
    }

    #[\Override]
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    #[\Override]
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    #[\Override]
    public function setAuthorId(int $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }

    #[\Override]
    public function getSamplePermalink(): string
    {
        return $this->samplePermalink;
    }

    #[\Override]
    public function setSamplePermalink(string $samplePermalink): self
    {
        if ('' === $samplePermalink) {
            throw new \RuntimeException();
        }

        $this->samplePermalink = $samplePermalink;

        return $this;
    }

    #[\Override]
    public function getNumericPermalink(): string
    {
        $result = str_replace($this->name, (string) $this->id, $this->samplePermalink);

        if ('' === $result) {
            throw new \RuntimeException();
        }

        return $result;
    }

    #[\Override]
    public function getPostDate(): \DateTime
    {
        return $this->postDate;
    }

    #[\Override]
    public function setPostDate(\DateTime $postDate): self
    {
        $this->postDate = $postDate;

        return $this;
    }
}

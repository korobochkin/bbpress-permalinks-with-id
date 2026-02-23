<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Type;

interface PostInterface
{
    public function getId(): int;

    /**
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    public function setId(int $id): self;

    public function getType(): Type;

    public function getStatus(): Status;

    public function setStatus(Status $status): self;

    public function getTitle(): string;

    public function setTitle(string $title): self;

    public function getContent(): string;

    public function setContent(string $content): self;

    public function getName(): string;

    public function setName(string $name): self;

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getAuthorId(): int;

    /**
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    public function setAuthorId(int $authorId): self;

    /**
     * @return non-empty-string
     */
    public function getSamplePermalink(): string;

    /**
     * @psalm-suppress PossiblyUnusedReturnValue
     *
     * @throws \RuntimeException
     */
    public function setSamplePermalink(string $samplePermalink): self;

    /**
     * @return non-empty-string
     *
     * @throws \RuntimeException
     */
    public function getNumericPermalink(): string;
}

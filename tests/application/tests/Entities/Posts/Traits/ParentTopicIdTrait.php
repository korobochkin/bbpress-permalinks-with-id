<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Traits;

trait ParentTopicIdTrait
{
    private int $parentTopicId;

    public function getParentTopicId(): int
    {
        return $this->parentTopicId;
    }

    public function setParentTopicId(int $parentTopicId): self
    {
        $this->parentTopicId = $parentTopicId;

        return $this;
    }
}

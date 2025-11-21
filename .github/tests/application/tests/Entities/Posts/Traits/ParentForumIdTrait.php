<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Traits;

trait ParentForumIdTrait
{
    private int $parentForumId;

    public function getParentForumId(): int
    {
        return $this->parentForumId;
    }

    public function setParentForumId(int $parentForumId): self
    {
        $this->parentForumId = $parentForumId;

        return $this;
    }
}

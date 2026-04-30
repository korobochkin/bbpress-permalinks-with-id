<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\WordPressPostInterface;

final class Page extends AbstractPost implements WordPressPostInterface
{
    #[\Override]
    public function getType(): Type
    {
        return Type::Page;
    }
}

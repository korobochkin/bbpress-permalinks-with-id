<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\AbstractPost;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\PostInterface;

class PostUtilities
{
    /**
     * @template Type of AbstractPost
     *
     * @param Type $post
     *
     * @return Type
     */
    public static function copyAndEditTitleAndContent(PostInterface $post): AbstractPost
    {
        $editedPost = clone $post;

        $editTitleString = implode('_', ['EDIT', Random::positiveInteger()]);
        $editContentString = implode('_', ['EDIT', Random::positiveInteger()]);

        /*
         * Do not make titles too long. bbPress validates length of titles.
         * @see bbp_is_title_too_long
         */
        $editedPost->setTitle(implode(' ', [$post->getTitle(), $editTitleString]));
        $editedPost->setContent(implode(' ', [$editContentString, $post->getContent(), $editContentString]));

        return $editedPost;
    }
}

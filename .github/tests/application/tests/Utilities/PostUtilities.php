<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\AbstractPost;

class PostUtilities
{
    /**
     * @template Type of AbstractPost
     *
     * @param Type $post
     *
     * @return Type
     */
    public static function copyAndEditTitleAndContent(AbstractPost $post): AbstractPost
    {
        $editedPost = clone $post;

        $editTitleString = implode('_', ['EDIT', Random::positiveInteger()]);
        $editContentString = implode('_', ['EDIT', Random::positiveInteger()]);

        $editedPost->setTitle(implode(' ', [$editTitleString, $post->getTitle(), $editTitleString]));
        $editedPost->setContent(implode(' ', [$editContentString, $post->getContent(), $editContentString]));

        return $editedPost;
    }
}

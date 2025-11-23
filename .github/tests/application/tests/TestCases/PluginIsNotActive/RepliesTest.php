<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractHttpTestCase;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowserActions;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class RepliesTest extends AbstractHttpTestCase
{
    /**
     * @see TopicsTest::assertPreConditions()
     */
    protected function assertPreConditions(): void
    {
        parent::assertPreConditions();

        foreach (ForumDataProvider::getReplies() as [$forum, $topic, $reply]) {
            /*
             * @var Forum $forum
             * @var Topic $topic
             * @var Reply $reply
             */
            $reply->setParentForumId($forum->getId());
            $reply->setParentTopicId($topic->getId());
        }
    }

    #[Attributes\DependsOnClass(TopicsTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getReplies')]
    public function testCreateReply(Forum $forum, Topic $topic, Reply $reply): void
    {
        $this->browsers->admin->followRedirects(true);
        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $reply);
        $this->assertPageStatusIs200($this->browsers->admin->getResponse());

        $this->assertIsInt($reply->getId());
        $this->assertSame(Status::Publish, $reply->getStatus());
        $this->assertSampleLinkIsOk($reply);
    }
}

<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Random;

class ForumDataProvider
{
    private const int KEY_FORUM = 0;

    private const int KEY_TOPIC = 1;

    public static self $instance;

    private int $numberOfForums = 2;

    private int $numberOfTopics = 2;

    private int $numberOfReplies = 2;

    /**
     * @var array<int, array{0: Forum, 1: Topic[]}>
     */
    private array $data = [];

    /**
     * @return \Generator<int, array{Forum}, mixed, void>
     */
    public static function getForums(): \Generator
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->build();
        }

        return self::$instance->forumsGenerator();
    }

    /**
     * @return \Generator<int, array{Forum, Topic}, mixed, void>
     */
    public static function getTopics(): \Generator
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->build();
        }

        return self::$instance->topicsGenerator();
    }

    private function build(): void
    {
        for ($i = 0; $i < $this->numberOfForums; ++$i) {
            $topics = [];

            for ($j = 0; $j < $this->numberOfTopics; ++$j) {
                $topics[] = $this->buildTopic($i, $j);
            }

            $this->data[$i] = [
                self::KEY_FORUM => $this->buildForum($i),
                self::KEY_TOPIC => $topics,
            ];
        }
    }

    private function buildForum(int $forumIteration): Forum
    {
        $post = new Forum();
        $random = Random::positiveInteger();
        $post
            ->setTitle('Forum # '.$forumIteration.'. '.$random)
            ->setContent(Random::sentence())
            ->setName('forum-slug-'.$forumIteration.'-'.$random.'-end')
        ;

        return $post;
    }

    private function buildTopic(int $forumIteration, int $topicIteration): Topic
    {
        $topic = new Topic();
        $random = Random::positiveInteger();

        $topic
            ->setTitle('Topic # '.$forumIteration.'-'.$topicIteration.'. '.$random)
            ->setContent(Random::sentence())
            ->setName('topic-slug-'.$forumIteration.'-'.$topicIteration.'-'.$random.'-end')
        ;

        return $topic;
    }

    /**
     * @return \Generator<int, array{Forum}, mixed, void>
     */
    private function forumsGenerator(): \Generator
    {
        foreach ($this->data as $i => $pair) {
            yield $i => [$pair[self::KEY_FORUM]];
        }
    }

    /**
     * @return \Generator<int, array{Forum, Topic}, mixed, void>
     */
    private function topicsGenerator(): \Generator
    {
        foreach ($this->data as $forumAndTopics) {
            foreach ($forumAndTopics[self::KEY_TOPIC] as $i => $topic) {
                yield $i => [
                    self::KEY_FORUM => $forumAndTopics[self::KEY_FORUM],
                    self::KEY_TOPIC => $topic,
                ];
            }
        }
    }
}

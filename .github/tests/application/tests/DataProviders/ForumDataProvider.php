<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Type;
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
     * @var array<int, array{0: Post, 1: Post[]}>
     */
    private array $data = [];

    public static function getForums(): \Generator
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->build();
        }

        return self::$instance->forumsGenerator();
    }

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

    private function buildForum(int $forumIteration): Post
    {
        $post = new Post();
        $random = Random::positiveInteger();
        $post
            ->setType(Type::Forum)
            ->setTitle('Forum # '.$forumIteration.'. '.$random)
            ->setContent(Random::sentence())
            ->setName('forum-slug-'.$forumIteration.'-'.$random.'-end')
        ;

        return $post;
    }

    private function buildTopic(int $forumIteration, int $topicIteration): Post
    {
        $topic = new Post();
        $random = Random::positiveInteger();

        $topic
            ->setType(Type::Topic)
            ->setTitle('Topic # '.$forumIteration.'-'.$topicIteration.'. '.$random)
            ->setContent(Random::sentence())
            ->setName('topic-slug-'.$forumIteration.'-'.$topicIteration.'-'.$random.'-end')
        ;

        return $topic;
    }

    private function forumsGenerator(): \Generator
    {
        foreach ($this->data as $i => $pair) {
            yield $i => [$pair[self::KEY_FORUM]];
        }
    }

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

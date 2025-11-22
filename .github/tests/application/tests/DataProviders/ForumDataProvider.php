<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Random;

class ForumDataProvider
{
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
            $topicsAndReplies = [];

            for ($j = 0; $j < $this->numberOfTopics; ++$j) {
                $replies = [];

                for ($k = 0; $k < $this->numberOfReplies; ++$k) {
                    $replies[] = $this->buildReply($i, $j, $k);
                }

                $topicsAndReplies[] = [
                    $this->buildTopic($i, $j),
                    $replies,
                ];
            }

            $this->data[$i] = [
                $this->buildForum($i),
                $topicsAndReplies,
            ];
        }
    }

    private function buildForum(int $forumIteration): Forum
    {
        $post = new Forum();
        $random = Random::positiveInteger();
        $post
            ->setTitle(implode(' ', ['Forum #', $forumIteration, $random]))
            ->setContent(Random::sentence())
            ->setName(implode('-', ['forum-slug', $forumIteration, $random, 'end']))
        ;

        return $post;
    }

    private function buildTopic(int $forumIteration, int $topicIteration): Topic
    {
        $topic = new Topic();
        $random = Random::positiveInteger();

        $topic
            ->setTitle(implode(' ', ['Topic #', implode('_', [$forumIteration, $topicIteration]), $random]))
            ->setContent(Random::sentence())
            ->setName(implode('-', ['topic-slug', $forumIteration, $topicIteration, $random, 'end']))
        ;

        return $topic;
    }

    /**
     * @return \Generator<int, array{Forum}, mixed, void>
     */
    private function forumsGenerator(): \Generator
    {
        foreach ($this->data as $i => [$forum]) {
            yield $i => [$forum];
        }
    }

    /**
     * @return \Generator<int, array{Forum, Topic}, mixed, void>
     */
    private function topicsGenerator(): \Generator
    {
        $i = 0;
        foreach ($this->data as [$forum, $topicsAndReplies]) {
            foreach ($topicsAndReplies as [$topic, $replies]) {
                yield $i++ => [$forum, $topic];
            }
        }
    }
}

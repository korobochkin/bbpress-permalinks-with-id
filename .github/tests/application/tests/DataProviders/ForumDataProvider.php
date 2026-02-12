<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Random;

final class ForumDataProvider
{
    public static self $instance;

    private int $numberOfForums = 2;

    private int $numberOfTopics = 20;

    private int $numberOfReplies = 20;

    private int $topicsPerPage = 15;

    private int $repliesPerPage = 15;

    /**
     * @var array<int, array{0: Forum, 1: Topic[]}>
     */
    private array $data = [];

    /**
     * @return \Generator<int, array{Forum}, mixed, void>
     */
    public static function getForums(): \Generator
    {
        self::prepareInstance();

        return self::$instance->forumsGenerator();
    }

    public static function getTopicsPaged(): \Generator
    {
        self::prepareInstance();

        return self::$instance->topicsPagedGenerator();
    }

    /**
     * @return \Generator<int, array{Forum, Topic}, mixed, void>
     */
    public static function getTopics(): \Generator
    {
        self::prepareInstance();

        return self::$instance->topicsGenerator();
    }

    public static function getRepliesPaged(): \Generator
    {
        self::prepareInstance();

        return self::$instance->repliesPagedGenerator();
    }

    public static function getReplies(): \Generator
    {
        self::prepareInstance();

        return self::$instance->repliesGenerator();
    }

    public static function getRepliesEdit(): \Generator
    {
        self::prepareInstance();

        return self::$instance->repliesEditGenerator();
    }

    private static function prepareInstance(): void
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->build();
        }
    }

    private function build(): void
    {
        for ($i = 0; $i < $this->numberOfForums; ++$i) {
            $topicsAndReplies = [];

            for ($j = 0; $j < $this->numberOfTopics; ++$j) {
                $replies = [];

                if ($j < 2) {
                    $numberOfReplies = $this->numberOfReplies;
                } else {
                    $numberOfReplies = 3;
                }

                for ($k = 0; $k < $numberOfReplies; ++$k) {
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
        $number = implode('_', [$forumIteration, $topicIteration]);

        $topic
            ->setTitle(implode(' ', ['Topic #', $number, $random]))
            ->setContent($number.' '.Random::sentence())
            ->setName(implode('-', ['topic-slug', $forumIteration, $topicIteration, $random, 'end']))
        ;

        return $topic;
    }

    private function buildReply(int $forumIteration, int $topicIteration, int $replyIteration): Reply
    {
        $reply = new Reply();
        $random = Random::positiveInteger();
        $number = implode('_', [$forumIteration, $topicIteration, $replyIteration]);

        $reply
            ->setTitle(implode(' ', ['Reply #', $number, $random]))
            ->setContent($number.' '.Random::sentence())
            ->setName(implode('-', ['reply-slug', $forumIteration, $topicIteration, $replyIteration, $random, 'end']))
        ;

        return $reply;
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

    /**
     * @return \Generator<int, array{Forum, int, Topic[]}, mixed, void>
     */
    private function topicsPagedGenerator(): \Generator
    {
        foreach ($this->data as $i => [$forum, $topicsAndReplies]) {
            $topicsCounter = count($topicsAndReplies);

            if ($topicsCounter > $this->topicsPerPage) {
                $numberOfPages = (int) ceil($topicsCounter / $this->topicsPerPage);

                for ($page = 1; $page <= $numberOfPages; ++$page) {
                    // Calculate offset from the end
                    $offset = $topicsCounter - ($page * $this->topicsPerPage);
                    $length = $this->topicsPerPage;

                    // If offset goes negative, adjust length and set offset to 0
                    if ($offset < 0) {
                        $length = $this->topicsPerPage + $offset; // reduces length by the overflow
                        $offset = 0;
                    }

                    $slice = array_slice($topicsAndReplies, $offset, $length);

                    $topicsOnPage = array_map(function ($topicAndReplies) {
                        return $topicAndReplies[0];
                    }, $slice);

                    yield implode('_', ['forum', $i, 'page', $page]) => [$forum, $page, $topicsOnPage];
                }
            }
        }
    }

    /**
     * @return \Generator<int, array{Forum, Topic, Reply}, mixed, void>
     */
    private function repliesGenerator(): \Generator
    {
        $i = 0;
        foreach ($this->data as [$forum, $topicsAndReplies]) {
            foreach ($topicsAndReplies as [$topic, $replies]) {
                foreach ($replies as $reply) {
                    yield $i++ => [$forum, $topic, $reply];
                }
            }
        }
    }

    /**
     * @return \Generator<int, array{Forum, Topic, int, Reply}, mixed, void>
     */
    private function repliesPagedGenerator(): \Generator
    {
        $i = 0;
        foreach ($this->data as [$forum, $topicsAndReplies]) {
            foreach ($topicsAndReplies as [$topic, $replies]) {
                $repliesCounter = count($replies) + 1; // + 1 is a topic itself

                if ($repliesCounter > $this->repliesPerPage) {
                    $numberOfPages = (int) ceil($repliesCounter / $this->repliesPerPage);
                    $offset = 0;

                    for ($page = 1; $page <= $numberOfPages; ++$page) {
                        $length = 1 === $page ? $this->repliesPerPage - 1 : $this->repliesPerPage;

                        $slice = array_slice($replies, $offset, $length);

                        $offset += $length;

                        yield $i++ => [$forum, $topic, $page, $slice];
                    }
                }
            }
        }
    }

    /**
     * @return \Generator<int, array{Forum, Topic, Reply}, mixed, void>
     */
    private function repliesEditGenerator(): \Generator
    {
        $i = 0;
        foreach ($this->data as [$forum, $topicsAndReplies]) {
            if (empty($topicsAndReplies)) {
                continue;
            }

            $topicIndices = array_unique([array_key_first($topicsAndReplies), array_key_last($topicsAndReplies)]);

            foreach ($topicIndices as $topicIndex) {
                [$topic, $replies] = $topicsAndReplies[$topicIndex];
                if (empty($replies)) {
                    continue;
                }

                $replyIndices = array_unique([array_key_first($replies), array_key_last($replies)]);

                foreach ($replyIndices as $replyIndex) {
                    yield $i++ => [$forum, $topic, $replies[$replyIndex]];
                }
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Random;

final class ForumDataProvider
{
    private static self $instance;

    private int $numberOfForums = 2;

    private int $numberOfTopics = 20;

    private int $numberOfReplies = 20;

    private int $topicsPerPage = 15;

    private int $repliesPerPage = 15;

    /**
     * @var non-empty-array<int<0, max>, array{0: Forum, 1: list<array{0: Topic, 1: list<Reply>}>}>
     */
    private array $data;

    public function __construct()
    {
        $data = [];

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
                    0 => $this->buildTopic($i, $j),
                    1 => $replies,
                ];
            }

            $data[$i] = [
                0 => $this->buildForum($i),
                1 => $topicsAndReplies,
            ];
        }

        assert([] !== $data);

        $this->data = $data;
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return \Generator<int<0, max>, array{Forum}, mixed, void>
     */
    public static function getForums(): \Generator
    {
        return self::$instance->forumsGenerator();
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return \Generator<non-empty-string, array{Forum, int<1, max>, list<Topic>}, mixed, void>
     */
    public static function getTopicsPaged(): \Generator
    {
        return self::$instance->topicsPagedGenerator();
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return \Generator<int<0, max>, array{Forum, Topic}, mixed, void>
     */
    public static function getTopics(): \Generator
    {
        return self::$instance->topicsGenerator();
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return \Generator<int<0, max>, array{Forum, Topic, int<1, max>, list<Reply>}, mixed, void>
     */
    public static function getRepliesPaged(): \Generator
    {
        return self::$instance->repliesPagedGenerator();
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return \Generator<int<0, max>, array{Forum, Topic, Reply}, mixed, void>
     */
    public static function getReplies(): \Generator
    {
        return self::$instance->repliesGenerator();
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return \Generator<int<0, max>, array{Forum, Topic, Reply}, mixed, void>
     */
    public static function getRepliesEdit(): \Generator
    {
        return self::$instance->repliesEditGenerator();
    }

    public static function prepareInstance(): void
    {
        self::$instance = new self();
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
     * @return \Generator<int<0, max>, array{Forum}, mixed, void>
     */
    private function forumsGenerator(): \Generator
    {
        foreach ($this->data as $i => [$forum]) {
            yield $i => [$forum];
        }
    }

    /**
     * @return \Generator<int<0, max>, array{Forum, Topic}, mixed, void>
     */
    private function topicsGenerator(): \Generator
    {
        $i = 0;
        foreach ($this->data as [$forum, $topicsAndReplies]) {
            foreach ($topicsAndReplies as [$topic]) {
                yield $i++ => [$forum, $topic];
            }
        }
    }

    /**
     * @return \Generator<non-empty-string, array{Forum, int<1, max>, list<Topic>}, mixed, void>
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
     * @return \Generator<int<0, max>, array{Forum, Topic, Reply}, mixed, void>
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
     * @return \Generator<int<0, max>, array{Forum, Topic, int<1, max>, list<Reply>}, mixed, void>
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
     * @return \Generator<int<0, max>, array{Forum, Topic, Reply}, mixed, void>
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

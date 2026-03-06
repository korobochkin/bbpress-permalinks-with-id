<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Logs;

final readonly class LogEntry
{
    /**
     * @psalm-suppress PossiblyUnusedProperty
     *
     * @var string timestamp in RFC 3339 format
     */
    public string $timestamp;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     *
     * @var string Syslog severity name (e.g. "err", "info", "notice").
     */
    public string $severity;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     *
     * @var int<0, 7> Syslog severity code (e.g. 3 for error, 6 for info).
     */
    public int $severityCode;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     *
     * @var string syslog tag identifying the source container
     */
    public string $tag;

    /**
     * @var string the log message content
     */
    public string $message;

    /**
     * @param int<0, 7> $severityCode
     */
    public function __construct(
        string $timestamp,
        string $severity,
        int $severityCode,
        string $tag,
        string $message,
    ) {
        $this->timestamp = $timestamp;
        $this->severity = $severity;
        $this->severityCode = $severityCode;
        $this->tag = $tag;
        $this->message = $message;
    }
}

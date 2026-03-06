<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Logs\LogEntry;

final class WordPressServerLogs
{
    private const string LOG_DIR = '/var/log/remote';

    private const string ALL_LOG = self::LOG_DIR.'/wordpress-all.log';

    private const string ERRORS_LOG = self::LOG_DIR.'/wordpress-errors.log';

    private int $errorCheckpoint = 0;

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return list<LogEntry>
     *
     * @throws \UnexpectedValueException
     */
    public function getAll(): array
    {
        return $this->readLog(self::ALL_LOG);
    }

    /**
     * @return list<LogEntry>
     *
     * @throws \UnexpectedValueException
     */
    public function getErrors(): array
    {
        return $this->readLog(self::ERRORS_LOG);
    }

    /**
     * @throws \UnexpectedValueException
     */
    public function checkpoint(): void
    {
        $this->errorCheckpoint = count($this->getErrors());
    }

    /**
     * @return list<LogEntry>
     *
     * @throws \UnexpectedValueException
     */
    public function getErrorsSinceCheckpoint(): array
    {
        return array_slice($this->getErrors(), $this->errorCheckpoint);
    }

    /**
     * @return list<LogEntry>
     *
     * @throws \UnexpectedValueException
     */
    private function readLog(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (false === $lines) {
            return [];
        }

        $entries = [];

        foreach ($lines as $line) {
            $entries[] = $this->parseLine($line);
        }

        return $entries;
    }

    /**
     * @throws \UnexpectedValueException
     */
    private function parseLine(string $line): LogEntry
    {
        $data = json_decode($line, true);

        if (!is_array($data)) {
            throw new \UnexpectedValueException('Invalid $data object');
        }

        $severityCode = (int) ($data['severity_code'] ?? 0);

        if ($severityCode < 0 || $severityCode > 7) {
            throw new \UnexpectedValueException('Bad "severity_code" in log record');
        }

        return new LogEntry(
            timestamp: (string) ($data['timestamp'] ?? throw new \UnexpectedValueException('Empty "timestamp" in log record')),
            severity: (string) ($data['severity'] ?? throw new \UnexpectedValueException('Empty "severity" in log record')),
            severityCode: $severityCode,
            tag: (string) ($data['tag'] ?? throw new \UnexpectedValueException('Empty "tag" in log record')),
            message: (string) ($data['message'] ?? ''),
        );
    }
}

<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

final class WordPressServerLogs
{
    private const string LOG_DIR = '/var/log/remote';

    private const string ALL_LOG = self::LOG_DIR.'/wordpress-all.log';

    private const string ERRORS_LOG = self::LOG_DIR.'/wordpress-errors.log';

    /**
     * @return list<\stdClass>
     */
    public function getAll(): array
    {
        return $this->readLog(self::ALL_LOG);
    }

    /**
     * @return list<\stdClass>
     */
    public function getErrors(): array
    {
        return $this->readLog(self::ERRORS_LOG);
    }

    public function hasErrors(): bool
    {
        return [] !== $this->getErrors();
    }

    /**
     * @return list<\stdClass>
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
            $decoded = json_decode($line);

            if ($decoded instanceof \stdClass) {
                $entries[] = $decoded;
            }
        }

        return $entries;
    }
}

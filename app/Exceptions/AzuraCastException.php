<?php

namespace App\Exceptions;

use Exception;

class AzuraCastException extends Exception
{
    public static function connectionFailed(string $message): self
    {
        return new self("Failed to connect to AzuraCast API: {$message}");
    }

    public static function requestFailed(string $message, int $statusCode = 0): self
    {
        return new self("AzuraCast API request failed (HTTP {$statusCode}): {$message}");
    }

    public static function invalidResponse(string $message): self
    {
        return new self("Invalid response from AzuraCast API: {$message}");
    }

    public static function notConfigured(): self
    {
        return new self('AzuraCast API is not properly configured. Please check your environment variables.');
    }
}

<?php

namespace app\constants;

use Exception;

class LogCategories
{
    const MESSAGE_TEMPLATE = "Метод: %s.\nОшибка: %s.\nСтрока: %s.\nТрассировка: %s";

    const API_INTEGRATION_TELEGRAM = 'api.integration.telegram';

    /**
     * @param Exception $e
     * @param string $method
     * @param string|null $errorDescription
     * @return string
     */
    public static function createLogMessage(Exception $e, string $method, ?string $errorDescription = null): string
    {
        $errorMessage = $e->getMessage();
        if ($errorDescription) {
            $errorMessage .= sprintf(".\n%s", $errorDescription);
        }
        return sprintf(
            self::MESSAGE_TEMPLATE,
            $method,
            $errorMessage,
            $e->getLine(),
            $e->getTraceAsString()
        );
    }
}
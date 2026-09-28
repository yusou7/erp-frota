<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setFlash(
        string $tipo,
        string $mensagem
    ): void {
        self::start();

        $_SESSION['flash'] = [
            'tipo' => $tipo,
            'mensagem' => $mensagem,
        ];
    }

    public static function getFlash(): ?array
    {
        self::start();

        $flash = $_SESSION['flash'] ?? null;

        unset($_SESSION['flash']);

        return $flash;
    }
}
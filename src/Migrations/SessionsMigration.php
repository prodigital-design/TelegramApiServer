<?php

namespace TelegramApiServer\Migrations;

use TelegramApiServer\Client;

class SessionsMigration
{
    public static function move($rootDir = ROOT_DIR)
    {
        foreach (glob("$rootDir/*" . Client::$sessionExtension) as $oldFile) {
            preg_match(
                '~^' . "{$rootDir}(?'session'.*)" . preg_quote(Client::$sessionExtension, '\\') . '$~',
                $oldFile,
                $matches
            );

            if ($session = $matches['session'] ?? null) {
                $session = Client::getSessionFile($session);
                Client::checkOrCreateSessionFolder($session);

                rename($oldFile, "{$rootDir}/{$session}");
                rename("{$oldFile}.lock", "{$rootDir}/{$session}.lock");
            }
        }
    }

}
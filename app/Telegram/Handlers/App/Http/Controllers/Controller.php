<?php

namespace App\Telegram\Handlers\App\Http\Controllers;

use SergiX44\Nutgram\Nutgram;

class Controller
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage('This is an handler!');
    }
}

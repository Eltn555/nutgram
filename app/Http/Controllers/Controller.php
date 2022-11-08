<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Conversations\Conversation;

class MyConversation extends Conversation {

        public function start(Nutgram $bot)
        {
            $bot->sendMessage('This is the first step!');
            $this->next('secondStep');
        }

        public function secondStep(Nutgram $bot)
        {
            $bot->sendMessage('Bye!');
            $this->end();
        }
    }

$bot = new Nutgram($_ENV['5679007895:AAFr7xk3m_ekcF_5b9KmAdxXMkbLfX-rZ6I']);

$bot->onCommand('start', MyConversation::class);

$bot->run();
class Controller extends BaseController
{

}

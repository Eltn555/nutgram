<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\MessageTypes;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

use SergiX44\Nutgram\Conversations\Conversation;

$fileid = "";
$temporary = [];

$bot->onCommand('start', function (Nutgram $bot) {
    $kb = ['reply_markup' =>
        ['keyboard' => [[
            ['text' => "O'zbek tili"],
            ['text' => 'English']
        ]], 'resize_keyboard' => true]
    ];
    $bot->sendMessage("Assalomu alekum, iltimos tilni tanlang", $kb);
});

$bot->onText("O'zbek tili", function (Nutgram $bot) {
    global $fileid;
    $fileid = "";
    $lang = "uz";
    $kb = ['reply_markup' =>
        ['keyboard' => [[
            ['text' => 'contact', 'request_contact' => true]
        ]], 'resize_keyboard' => true]
    ];
    $bot->nextStep('askContact');
    $bot->sendMessage("Assalomu alekum, siz bu bot orqali o'z avtomobilingizni sotish uchun e'lon berishingiz mumkun", $kb);
});

//function askContact(Nutgram $bot)
//{
//
//    $number = $bot->getMessage()->getContact()->getPhoneNumber();
//    $name = $bot->getMessage()->getContact()->getFirstName();
//
//    $mId = $bot->getMessage()->getMessageId();
//    $chat_id = $bot->getMessage()->getChat()->getId();
//
//    $username = $bot->getMessage()->getChat()->getUsername();
//    $bot->sendMessage("Hi $name, what a wonderful name! Your number is $number Id:  @$username, ".$chat_id." chat_id, mesID: ".$mId);
//    $bot->forwardMessage('@moshinabozorpll', $chat_id, $mId);
//    $bot->sendMessage("Mashina rasmini yuboring");
//    $bot->nextStep('askPhoto');
//}

$bot->onText("O'zbek tili", function (Nutgram $bot) {

    $kb2 = ['reply_markup' =>
        ['keyboard' => [
            [
              ['text' => 'Nomeringizni yuboring','request_contact'=> true],
            ]
        ], 'resize_keyboard' => true]
    ];
    $bot->sendMessage("Assalomu alekum, siz bu bot orqali o'z avtomobilingizni sotish uchun e'lon berishingiz mumkun", $kb2);
    $bot->stepConversation('askContact');
});

function askContact(Nutgram $bot) {
    $number = $bot->message()->contact->phone_number;
    $name = $bot->user()->first_name;
    $name .= $bot->user()->last_name;

    $mId = $bot->messageId();
    $chat_id = $bot->chatId();

    $username = $bot->user()->username;
    $bot->sendMessage("Hi $name, what a wonderful name! Your number is +$number Id:  @$username, Chat_id: $chat_id, mesID: $mId");
    $bot->forwardMessage('@moshinabozorpll', $chat_id, $mId);
    $bot->sendMessage("Mashina rasmini yuboring");
    $bot->stepConversation('askPhoto');
};

//$bot->onMessageType(MessageTypes::PHOTO, function (Nutgram $bot){
//    $photos = $bot->message()->photo;
//    $bot->sendMessage('Nice Pic!');
//});

function askPhoto(Nutgram $bot){
    global $fileid;
//    $chat_id = $bot->chatId();
//    $bot->sendMessage("chatid OK $chat_id");
//    $mId = $bot->messageId();
//    $bot->sendMessage("message OK $mId");



    if ($fileid == null){
        $fileid .= $bot->update()->message->photo[0]->file_id;
    }else{
        $fileid .= ", ".$bot->update()->message->photo[0]->file_id;
    }
    $bot->sendMessage("fileid OK $fileid");

    $kb = ['reply_markup' =>
        ['inline_keyboard' => [[
            ['callback_data' => 'askMark', 'text' => 'Keyingi bosqich']
        ]], 'resize_keyboard' => true]
    ];
    file_put_contents('D:\JSONs\\' . $bot->update()->message->photo[0]->file_id  . '.json', json_encode($bot->update()));

    $bot->sendMessage("Rahmat rasmingizni qabul qildik. ", $kb);
//    $bot->sendMessage("chatid - $photo");
//    $bot->sendMessage("Message_Id - $mId");
//    $bot->sendMessage("File_id = $photo");


}

$bot->onCallbackQueryData('askMark', function (Nutgram $bot) {
    global $fileid;

    $media = [
        [
            "photo",
            "AgACAgQAAxkBAAIFPWNp7VTAD7-54roTqTraKul7SCcsAAJ7uTEbXZ5QU6QQBsPxzoUMAQADAgADcwADKwQ"
        ],
        [
            "photo",
            "AgACAgQAAxkBAAIFPmNp7VQeXvFhV-h7avOQ0Sj7eLYEAAJ8uTEbXZ5QU8zUVb4s6POdAQADAgADcwADKwQ"
        ]
    ];

    $group = [
        [
            "type"=> 'photo',
            "media"=>"AgACAgQAAxkBAAIGM2NqHB4q1WSGczf89hlQ72tes60lAAKDuTEbXZ5QU4y_wdDuv2SqAQADAgADcwADKwQ"
        ],
        [
            "type"=> 'photo',
            "media"=>"AgACAgQAAxkBAAIGNGNqHB5YEd2cOmI0izZ9nK40HrJnAAJ7uTEbXZ5QU6QQBsPxzoUMAQADAgADcwADKwQ"
        ],
        [
            "type"=> 'photo',
            "media"=>"AgACAgQAAxkBAAIGNWNqHB5HoI3T2Hlq5At2_TudMR1xAAJ8uTEbXZ5QU8zUVb4s6POdAQADAgADcwADKwQ"
        ]
    ];

    $photos = $bot->message()->photo;
    $filearr = explode (",", $fileid);
    $photos = [];
    try{
    for($i=0; $i< count($filearr); $i++) {
        var_dump($filearr[$i]);
        $photos[$i] = [
            "type" => 'photo',
            "media" => (string)$filearr[$i]
        ];
    }
            $bot->sendMediaGroup($photos);
        }catch (Exception $e){
            $bot->sendMessage($e->getMessage());
        }

    print_r($photos);

    $bot->sendMessage("Nice! ".json_encode($filearr));
    //$bot->sendMediaGroup($media, ['chat_id' => '@moshinabozorpll']);
    //$bot->sendPhoto($fileid, ['chat_id' => '@moshinabozorpll']);

});

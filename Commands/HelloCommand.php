<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\SystemCommands\GenericCommand;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Exception\TelegramException;
use mysql_xdevapi\Result;

class HelloCommand extends UserCommand
{
    protected $name = 'hello';
    protected $description = 'Hello command';
    protected $usage = '/hello';
    protected $version = '1.2.0';
    protected $need_mysql = true;
    protected $private_only = true;
    protected $conversation;
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat = $message->getChat();
        $user = $message->getFrom();
        $text = trim($message->getText(true));
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $first_name = $message->getFrom()->getFirstName();

        $data = [
            'chat_id' => $chat_id,
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = $this->conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        $result = Request::emptyResponse();


        switch ($state) {
            case 0:
                if ($text === '' || !is_numeric($text)) {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['text'] = 'Type your IIN,please:';
                    if ($text !== '' && strlen($text) !== 8) {
                        $data['text'] = 'iin must be eight numeric digit';
                    }

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['iin'] = $text;
                $text = '';

            case 1:
                if($text === ''){
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = "Type number TS:";
                    $result = Request::sendMessage($data);
                    break;
                }
                $notes['ts'] = $text;
                $text = '';

        }
        return $result;
    }
}
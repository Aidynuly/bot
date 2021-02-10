<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class HelloCommand extends UserCommand
{
    protected $name = 'hello';
    protected $description = 'Hello command';
    protected $usage = '/hello';
    protected $version = '1.2.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $data = [
		'chat_id' => $chat_id,
		'text' => 'Ââåäèòå ÈÈÍ',
	];

	
        return Request::sendMessage($data);
    }
}
<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Exception\TelegramException;

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
	$first_name = $message->getFrom()->getFirstName();
	$text = $message->getText();
	
		
	$data = [
		'chat_id' => $chat_id,
		'text' => 'Welcome ' . $first_name . ",please first type your IIN number: ",
	];	
		
	
	return Request::sendMessage($data);

     }
}
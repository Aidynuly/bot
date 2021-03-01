<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Translation;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;


/**
 * Class StartCommand
 * @package Longman\TelegramBot\Commands\UserCommands
 */
class StartCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'send';

    /**
     * @var string
     */
    protected $description = 'Send command';

    /**
     * @var string
     */
    protected $usage = '/send';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @return ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        $chat    = $message->getChat();
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();
        $translation = Translation::messages();

        // Preparing response
        $data = [
            'chat_id'      => $chat_id,
            // Remove any keyboard by default
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

        if ($chat->isGroupChat() || $chat->isSuperGroup()) {
            // Force reply is applied by default so it can work with privacy on
            $data['reply_markup'] = Keyboard::forceReply(['selective' => true]);
        }

        // Conversation start
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        // Load any existing notes from this conversation
        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];

        // Load the current state of the conversation
        $state = $notes['state'] ?? 0;

        $result = Request::emptyResponse();

        switch ($state) {
            case 0:
                if ($text === '') {
                    $notes['lang'] = $text === $translation['ru']['yes'] ? 'ru' : 'kz';

                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['number_question'];
                    $data['reply_markup'] = (new Keyboard(['text' => $translation[$notes['lang']]['number'], 'request_contact' => true]))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['contact'] = $text;
                $text = '';

            case 1:
                if ($text === '') {
                    unset($notes['state']);
                    $data = ['chat_id'      => '500955797',
                        'text' => $notes['contact']
                    ];
                    $result = Request::sendMessage($data);
                    $this->conversation->stop;
                    break;
                }
        }

        return $result;
    }
}
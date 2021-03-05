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
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

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
                if ($text === '/start' || $text === '') {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['text'] = 'Р’С‹Р±РµСЂРёС‚Рµ СЏР·С‹Рє/РўС–Р»РґС– С‚Р°ТЈРґР°ТЈС‹Р·';
                    $data['reply_markup'] = (new Keyboard('Р СѓСЃСЃРєРёР№', 'ТљР°Р·Р°Т›'))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['chosen_lang'] = $text;
                $text = '';

            case 1:
                if ($text === '') {
                    $notes['state'] = 1;
                    $this->conversation->update();
                    $lang = $notes['chosen_lang'] === 'Р СѓСЃСЃРєРёР№' ? 'ru' : 'kz';
                    $data['text'] = $translation[$lang]['greeting']
                        .PHP_EOL
                        .$translation[$lang]['notice']
                    ;
                    $data['reply_markup'] = (new Keyboard($translation[$lang]['yes']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['answer'] = $text;
                $text = '';


            case 2:
                unset($notes['state']);
                $this->conversation->stop();
                $this->telegram->executeCommand('calculate');
        }

        return $result;
    }
}
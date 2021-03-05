<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Calculator;
use App\Translation;
use App\Validator;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Exception\TelegramException;


class CalculateCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'calculate';

    /**
     * @var string
     */
    protected $description = 'Calculate command';

    /**
     * @var string
     */
    protected $usage = '/calculate';

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
        $contact = $message->getContact();
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
                if ($text === $translation['ru']['yes'] || $text === $translation['kz']['yes'] || $text === '') {
                    $notes['lang'] = $text === $translation['ru']['yes'] ? 'ru' : 'kz';

                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['iin_question'];

                    $result = Request::sendMessage($data);
                    break;
                }

                if (!in_array($text, [
                        $translation['ru']['yes'],
                        $translation['kz']['yes'],
                        ''
                    ]) && !Validator::validateIIN($text)) {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['iin_error']
                        .PHP_EOL
                        .$translation[$notes['lang']]['try_again']
                    ;

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['iin'] = $text;
                $text = '';

            case 1:
                if ($text === '') {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['vehicle_question'];

                    $result = Request::sendMessage($data);
                    break;
                }


                if (!in_array($text, [
                        $translation['ru']['yes'],
                        $translation['kz']['yes'],
                        ''
                    ]) && !Validator::validateVehicleNumber($text)) {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['vehicle_error']
                        .PHP_EOL
                        .$translation[$notes['lang']]['try_again']
                    ;

                    $result = Request::sendMessage($data);
                    break;
                }


                $notes['vehicle'] = mb_strtoupper($text);
                $text = '';

            case 2:
                if ($text === '') {
                    $notes['state'] = 2;
                    $this->conversation->update();
                    $data['text'] = $translation[$notes['lang']]['experience_question'];
                    $data['reply_markup'] = (new Keyboard($translation[$notes['lang']]['experience_more'],$translation[$notes['lang']]['experience_less']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);
                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['chosen_experience'] = $text;
                $text = '';

            case 3:
                if ($text === '') {
                    $notes['state'] = 3;
                    $calculator = new Calculator($notes['iin'], $notes['vehicle'],$notes['chosen_experience']);
                    $result = $calculator->getPolicyPrice();


                    $data['text'] = $translation[$notes['lang']]['answer']." $result tg";
                    $result = Request::sendMessage($data);

                }
                $calculator = new Calculator($notes['iin'], $notes['vehicle'],$notes['chosen_experience']);
                $result = $calculator->getPolicyPrice();

                $notes['result'] = "$result tg";
                $text = '';

            case 4:
                if ($text === '') {
                    $notes['state'] = 4;
                    $this->conversation->update();
                    $data['text'] = $translation[$notes['lang']]['number_question'];
                    $keyboards[] = new Keyboard([
                        ['text' => $translation[$notes['lang']]['number'], 'request_contact' => true],
                    ]);

                    $keyboard = end($keyboards)
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);

                    $data['reply_markup'] = $keyboard;
                    $result = Request::sendMessage($data);
                    break;

                }

                $notes['contact'] = $text;
                $text = '';

            case 5:
                if ($text === '') {
                    $data['chat_id'] = '500955797';
                    $data['text'] = $notes['iin']
                        .PHP_EOL
                        .$notes['vehicle']
                        .PHP_EOL
                        .$notes['contact']
                        .PHP_EOL
                        .$notes['result'];
                    $result = Request::sendMessage($data);
                    unset($notes['state']);
                    $this->conversation->stop();
                }


        }

        return $result;
    }
}
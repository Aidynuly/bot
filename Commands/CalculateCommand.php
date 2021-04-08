<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Calculator;
use App\Translation;
use App\Validator;
use App\ApiFaker;
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
        $first_name = $user->getFirstName();
        $last_name = $user->getLastName();
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
        $sec_notes = &$this->conversation->notes;
        !is_array($sec_notes) && $sec_notes = [];
        // Load the current state of the conversation
        $state = $notes['state'] ?? 0;
        $notes['iin_collection'] = [];
        $notes['vehicle_collection'] = [];

        $result = Request::emptyResponse();

        switch ($state) {
            case 0:
                if ($text === $translation['ru']['yes'] || $text === $translation['kz']['yes'] || $text === '' || $text === $translation['ru']['drivers_add']
                    || $text === $translation['kz']['drivers_add'] ) {
                    $notes['lang'] = $text === $translation['ru']['yes'] ? 'ru' : 'kz' || $text === $translation['ru']['drivers_add'] ? 'ru' : 'kz';

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
                $notes['iin_collection'] = $text;
                $text = '';

            case 1:


                if (intval(ApiFaker::getClientData($notes['iin'])['bonus_malus']) === 0) {
                    $data['text'] = $translation[$notes['lang']]['iin_request']
                        .PHP_EOL
                        .$translation[$notes['lang']]['try_again'];

                    $result = Request::sendMessage($data);
                    break;
                }

                if ($text === '') {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['class_bm'] . ApiFaker::getClientData($notes['iin'])['bonus_malus'];
                    $result = Request::sendMessage($data);

                    $data['text'] = $translation[$notes['lang']]['drivers_count'];
                    $data['reply_markup'] = (new Keyboard($translation[$notes['lang']]['drivers_add'], $translation[$notes['lang']]['driver_one']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);
                    $result = Request::sendMessage($data);

                    break;
                }

                if ($text === $translation['ru']['drivers_add'] || $text === $translation['kz']['drivers_add']) {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['iin_question'];
                    $result = Request::sendMessage($data);

                    break;
                }



                if (Validator::validateIIN($text)) {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['class_bm'] . ApiFaker::getClientData($text)['bonus_malus'];
                    $result = Request::sendMessage($data);

                    $notes['iin_collection'][] = $text;
                    $data['text'] = $translation[$notes['lang']]['drivers_count'];

                    $data['reply_markup'] = (new Keyboard($translation[$notes['lang']]['drivers_add'],$translation[$notes['lang']]['driver_one']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);
                    $result = Request::sendMessage($data);
                    break;
                }
                $notes['iin_collection'][] = $text;
                $text = '';


            case 2:
                if ($text === '') {
                    $notes['state'] = 2;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['experience_question'];
                    $data['reply_markup'] = (new Keyboard($translation[$notes['lang']]['experience_more'], $translation[$notes['lang']]['experience_less']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);

                    $result = Request::sendMessage($data);

                    break;
                }

                $notes['chosen_experience'] = $text;
                $text = '';


            case 3:
                if ($text === '' || $text === $translation['ru']['vehicle_add'] || $text === $translation['kz']['vehicle_add']) {
                    $notes['state'] = 3;
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
                    $notes['state'] = 3;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['vehicle_error']
                        .PHP_EOL
                        .$translation[$notes['lang']]['try_again']
                    ;

                    $result = Request::sendMessage($data);
                    break;
                }
                $notes['vehicle'] = mb_strtoupper($text);
                array_push($notes['vehicle_collection'], $notes['vehicle']);
                $text = '';

            case 4:
                if ($text === '' && count($notes['iin_collection']) === 1 ) {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['vehicles_count'];
                    $data['reply_markup'] = (new Keyboard($translation[$notes['lang']]['vehicle_add'],$translation[$notes['lang']]['vehicle_one']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);
                    $result = Request::sendMessage($data);

                    break;
                }

                if ($text === $translation['ru']['vehicle_add'] || $text === $translation['kz']['vehicle_add']) {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['vehicle_question'];
                    $result = Request::sendMessage($data);

                    break;
                }

                if (Validator::validateVehicleNumber($text)) {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $data['text'] = $translation[$notes['lang']]['vehicles_count'];
                    $data['reply_markup'] = (new Keyboard($translation[$notes['lang']]['vehicle_add'],$translation[$notes['lang']]['vehicle_one']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);
                    $result = Request::sendMessage($data);

                    break;
                }

                array_push($notes['vehicle_collection'], $text);
                $text = '';


            case 5:
                if ($text === '') {
                    $notes['state'] = 5;
                    $calculator = new Calculator($notes['iin'], $notes['vehicle'], $notes['chosen_experience']);
                    $result = $calculator->getPolicyPrice();

                    $data['text'] = $translation[$notes['lang']]['answer'] . " $result  ₸"
                        .PHP_EOL
                        .$translation[$notes['lang']]['data'];

                    $result = Request::sendMessage($data);

                }

                $calculator = new Calculator($notes['iin'], $notes['vehicle'], $notes['chosen_experience']);
                $result = $calculator->getPolicyPrice();
                $notes['result'] = $result;
                $text = '';

            case 6:
                if ($message->getType() === 'contact') {

                    $notes['state'] = 6;

                    $data['text'] = $translation[$notes['lang']]['submit_number'];
                    $result = Request::sendMessage($data);

                    $data['text'] = $translation[$notes['lang']]['restart'];
                    $data['reply_markup'] = (new Keyboard('/start'))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);

                    $result = Request::sendMessage($data);

                    $notes['phone'] = $message->getContact()->getPhoneNumber();
                    $data['chat_id'] = '500955797';
                    $data['text'] = $translation[$notes['lang']]['name'] . $first_name
                        .PHP_EOL
                        .$translation[$notes['lang']]['iin'] . $notes['iin']
                        .PHP_EOL
                        .$translation[$notes['lang']]['ts'] . $notes['vehicle']
                        .PHP_EOL
                        .$translation[$notes['lang']]['summa'] . $notes['result'] . " ₸"
                        .PHP_EOL
                        .$translation[$notes['lang']]['mobile'] . $notes['phone'];
                    $result = Request::sendMessage($data);

                    break;
                }

                if ($text === '') {
                    $notes['state'] = 6;
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

        }

        return $result;
    }

    public static function getClassBm($arr)
    {
        $classes = [];
        for ($i = 0; $i < count($arr); $i++) {
            array_push($classes, ApiFaker::getClientData($arr[$i])['bonus_malus']);
        }
        return min($classes);
    }

    public static function checkClass($class, $arr) {
        for ($i = 0; $i < count($arr); $i++) {
            if (ApiFaker::getClientData($arr[$i])['bonus_malus'] === $class) {
                return $arr[$i];
            }
        }
    }


}
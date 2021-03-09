<?php


namespace App;

/**
 * Class Translation
 * @package App
 */
class Translation
{
    /**
     * @return \string[][]
     */
    public static function messages() : array
    {
        return [
            'ru' => [
                'greeting' => '👋 Здравствуйте, я бот, который поможет вам рассчитать сумму страхового полиса, хотите продолжить?',
                'yes' => '👌 Да',
                'iin_question' => '🗒️ Введите ИИН:',
                'vehicle_question' => '🚗 Введите гос номер автомобиля:',
                'answer' => '💰 Стоимость страхового полиса составляет:',
                'iin_error' => '⚠️ ИИН должен содержать 12 цифр!',
                'vehicle_error' => '⚠️ Корректно введите гос номер!',
                'try_again' => '😊 Попробуйте снова',
                'notice' => '*Продолжая, вы соглашаетесь на сбор информации!',
                'experience_question' => '🚗 Введите стаж вождения:',
                'experience_more' => '👌 Более 2 лет',
                'experience_less' => '👋 Менее 2 лет',
                'number_question' => '🗒️ Отправьте свой номер, чтобы наши консультанты связались с вами!',
                'number' => 'Отправить мой контакт',
                'submit_number' => 'Спасибо что поделились номером, совсем скоро наши консультанты свяжутся с вами!',
                'name' => 'Имя: ',
                'iin'  => 'ИИН: ',
                'ts'   => 'ТС: ',
                'summa'=> 'Сумма: ',
                'mobile'=>'Телефон: ',
                'restart'      => 'Нажмите Рассчитать еще, чтобы узнать сумму страхового полиса',
            ],
            'kz' => [
                'greeting' => '👋 Сәлеметсіз бе, мен сізге сақтандыру полисінің мөлшерін есептеуге көмектесетін ботпын, жалғастырғыңыз келе ме?',
                'yes' => '👌 Ия',
                'iin_question' => '🗒️ ЖСН енгізіңіз:',
                'vehicle_question' => '🚗 Көліктің мемлекеттік нөмірін енгізіңіз:',
                'answer' => '💰 Сақтандыру полисінің құны:',
                'iin_error' => '⚠️ ЖСН 12 цифрдан тұруы керек!',
                'vehicle_error' => '⚠️ Мемлекеттік нөмірді дұрыс енгізіңіз!!',
                'try_again' => '😊 Тағы да қайталап көріңіз',
                'notice' => '*Жалғастыру арқылы сіз ақпарат жинауға келісесіз!',
                'experience_question' => '🚗 Жүргізу тәжірибесін енгізіңіз:',
                'experience_more' => '👌 2 жылдан астам',
                'experience_less' => '👋 2 жылдан кем',
                'number_question' => '🗒️ Біздің кеңесшілер сізбен байланысу үшін нөміріңізді жіберіңіз!',
                'number' => 'Менің нөмерімді жіберу',
                'submit_number' => 'Нөміріңізді жібергеніңіз үшін рақмет, жақын арада біздің кеңесшілер сізбен байланысады!',
                'name' => 'Имя: ',
                'iin'  => 'ИИН: ',
                'ts'   => 'ТС: ',
                'summa'=> 'Сумма: ',
                'mobile'=>'Телефон: ',
                'restart'      => 'Сақтандыру полисінің мөлшерін білу үшін Толығырақ есептеу түймесін басыңыз',
            ]
        ];
    }
}
<?php

namespace App\Email;

class EmailCheckUrlDetector
{
    /**
     * @var array
     */
    protected $map = [
        'mail.ru'        => 'https://e.mail.ru/',
        'mail.ua'        => 'https://e.mail.ru/',
        'bk.ru'          => 'https://e.mail.ru/',
        'list.ru'        => 'https://e.mail.ru/',
        'inbox.ru'       => 'https://e.mail.ru/',
        'tut.by'         => 'https://mail.yandex.ru/',
        'yandex.ru'      => 'https://mail.yandex.ru/',
        'ya.ru'          => 'https://mail.yandex.ru/',
        'yandex.ua'      => 'https://mail.yandex.ua/',
        'yandex.by'      => 'https://mail.yandex.by/',
        'yandex.kz'      => 'https://mail.yandex.kz/',
        'yandex.com'     => 'https://mail.yandex.com/',
        'rambler.ru'     => 'https://mail.rambler.ru',
        'rambler.ua'     => 'https://mail.rambler.ru',
        'autorambler.ru' => 'https://mail.rambler.ru',
        'myrambler.ru'   => 'https://mail.rambler.ru',
        'lenta.ru'       => 'https://mail.rambler.ru',
        'ro.ru'          => 'https://mail.rambler.ru',
        'me.com'         => 'https://www.icloud.com/#mail',
        'icloud.com'     => 'https://www.icloud.com/#mail',
        'gmail.com'      => 'https://mail.google.com/',
        'googlemail.com' => 'https://mail.google.com/',
        'outlook.com'    => 'https://mail.live.com/',
        'hotmail.com'    => 'https://mail.live.com/',
        'live.ru'        => 'https://mail.live.com/',
        'live.com'       => 'https://mail.live.com/',
        //todo добавить хохлов i.ua и ukr.net
    ];

    /**
     * @param string $email
     * @return string
     */
    public function getUrlByEmail($email)
    {
        $host = substr($email, strrpos($email, '@') + 1);
        if (!isset($this->map[$host])) {
            return '';
        }
        return $this->map[$host];
    }

    //todo добавить возможность сообщать об описках в написании доменов

}

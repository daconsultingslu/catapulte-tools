<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Session;
use App\Entity\Tools\Signature\Qrcode;

class QrcodeService {
    public function generate(Event $event, Session $session, int $currentCount): Qrcode
    {
        $qrcode = new Qrcode();
        $qrcode->setSession($session);
        $qrcode->setToken(
            random_int(1, 9).bin2hex(random_bytes(4)).random_int(1, 9)
        );
        $qrcode->setDisplayedName($event->getId() . ' / ' . $session->getId() . ' / ' . strval($currentCount + 1));

        return $qrcode;
    }
}

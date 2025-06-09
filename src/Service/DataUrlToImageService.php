<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\Event;
use App\Entity\Session;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\User\User;

class DataUrlToImageService {
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly Filesystem $fileSystem,
    )
    {}

    public function saveDataToImage(Event $event, Session $session, User $user, $type, $dataToConvert) {

        $file = '';

        if($type == 11) {
            $sign1 = $this->kernel->getProjectDir().'/public/uploads/signature/'.$event->getId().'/'.$session->getId().'/signature1';
            if(!$this->fileSystem->exists($sign1)) {
                $this->fileSystem->mkdir($sign1);
            }
            $file .= $sign1;
        }
        elseif($type == 12) {
            $sign2 = $this->kernel->getProjectDir().'/public/uploads/signature/'.$event->getId().'/'.$session->getId().'/signature2';
            if(!$this->fileSystem->exists($sign2)) {
                $this->fileSystem->mkdir($sign2);
            }
            $file .= $sign2;
        }
        elseif($type == 2) {
            $decharge = $this->kernel->getProjectDir().'/public/uploads/signature/'.$event->getId().'/'.$session->getId().'/decharge';
            if(!$this->fileSystem->exists($decharge)) {
                $this->fileSystem->mkdir($decharge);
            }
            $file .= $decharge;
        }

        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $dataToConvert));

        $file .= '/'.$user->getId().'.jpg'; // or image.jpg

        // Save the image in a defined path
        file_put_contents($file,$data);
    }

}

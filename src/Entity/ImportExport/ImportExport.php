<?php

namespace App\Entity\ImportExport;

class ImportExport {

    private $file;

    /**
     * @return mixed
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void {
        $this->file = $file;
    }

}

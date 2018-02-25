<?php

class TemporaryFolder {

    protected $folder;

    public function get() {
        if (!$this->folder) {
            $this->folder = sys_get_temp_dir() . '/openacalendar'.rand();
            while(file_exists($this->folder)) {
                $this->folder = sys_get_temp_dir() . '/openacalendar'.rand();
            }
            mkdir($this->folder);
        }
        return $this->folder;
    }
}

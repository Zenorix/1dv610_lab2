<?php

namespace View;

class HeadView {
    private $type;
    private $language;
    private $title;

    public function __construct() {
        $this->title = 'Login Example';
        $this->language = 'en';
        $this->type = 'html';
    }

    public function generateHTML() {
        return '<!DOCTYPE '.$this->getType().'>
        <html lang="'.$this->getLanguage().'">
          <head>
            <meta charset="utf-8">
            <title>'.$this->getTitle().'</title>
          </head>';
    }

    public function getType() {
        return $this->type;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getTitle() {
        return $this->title;
    }
}

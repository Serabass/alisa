<?php

class AlisaResponse {
  public $text;
  public $tts;
  public $buttons = [];
  public $endSession = false;

  public function text($text) {
    $this->text = $text;
    return $this;
  }

  public function tts($text) {
    $this->tts = $text;
    return $this;
  }

  public function buttons($buttons) {
    $this->buttons = array_map(function ($button) {
      if (is_string($button)) {
        $button = [
          'title' => $button,
        ];
      }

      return $button;
    }, $buttons);
    return $this;
  }

  public function button($button) {
    if (is_string($button)) {
      $button = [
        'title' => $button,
      ];
    }
    $this->buttons[] = $button;
    return $this;
  }

  public function endSession($endSession = true) {
    $this->endSession = $endSession;
    return $this;
  }

  public function toArray() {
    return [
      'text' => $this->text,
      'tts' =>  $this->tts ?? $this->text,
      'buttons' => $this->buttons,
      'end_session' => $this->endSession
  ];
  }
}

function response() {
  return new AlisaResponse();
}
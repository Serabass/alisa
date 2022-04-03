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

  private function fixButton($button) {
    if (is_string($button)) {
      $button = [
        'title' => $button,
      ];
    }

    return $button;
  }

  public function buttons($buttons) {
    $this->buttons = array_map(function ($button) {
      return $this->fixButton($button);
    }, $buttons);
    return $this;
  }

  public function button($button) {
    $this->buttons[] = $this->fixButton($button);
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

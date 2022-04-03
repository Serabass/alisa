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
    $this->buttons = array_map(fn ($button) => $this->fixButton($button), $buttons);
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
    $result = [
      'text' => $this->text,
    ];

    if (!empty($this->tts)) {
      $result['tts'] = $this->tts;
    }

    if (!empty($this->buttons)) {
      $result['buttons'] = $this->buttons;
    }

    if (!empty($this->endSession)) {
      $result['end_session'] = $this->end_session;
    }

    return $result;
  }
}

function response() {
  return new AlisaResponse();
}

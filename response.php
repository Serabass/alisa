<?php

include_once 'attributes.php';
include_once 'traits/HasSetter.php';

/**
 * @method AlisaResponse text(string $text)
 * @method AlisaResponse tts(string $tts)
 * @method AlisaResponse endSession(string $endSession = true)
 */
class AlisaResponse
{
  use HasSetters;

  #[Setter]       public $text;
  #[Setter]       public $tts;
  #[Setter(true)] public $endSession = false;

  public $buttons = [];

  private function fixButton($button)
  {
    if (is_string($button)) {
      $button = [
        'title' => $button,
      ];
    }

    return $button;
  }

  public function buttons(...$buttons)
  {
    $this->buttons = array_map(fn ($button) => $this->fixButton($button), $buttons);
    return $this;
  }

  public function button($button)
  {
    $this->buttons[] = $this->fixButton($button);
    return $this;
  }

  public function toArray()
  {
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
      $result['end_session'] = $this->endSession;
    }

    return $result;
  }
}

function response()
{
  return new AlisaResponse();
}

function endSession($text)
{
  return response()
    ->text($text)
    ->tts($text)
    ->endSession();
}

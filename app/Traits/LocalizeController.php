<?php

namespace App\Traits;

trait LocalizeController
{
    private function localized()
    {

        if (! isset($this->data)) {

            $this->data = [];

        }

        $this->data['lc'] = app()->getLocale();

    }
}

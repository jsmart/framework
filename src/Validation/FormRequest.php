<?php

namespace JSmart\Validation;

class FormRequest
{
    public function __construct()
    {
        $this->run();
    }

    protected function run()
    {
        dump( $this->rules() );
    }
}

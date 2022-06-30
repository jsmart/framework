<?php

namespace JSmart\Core\Validation;

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

<?php

use Phalcon\Validation;

class LogsValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'date',
            new Phalcon\Validation\Validator\Date(
                [
                    'format' => Logs::DATE_FORMAT,
                    'cancelOnFail' => true,
                    'allowEmpty' => true
                ]
            )
        );
        $this->setFilters('date', Phalcon\Filter::FILTER_STRING);
    }
}

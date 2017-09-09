<?php

use \Phalcon\Mvc\Model;

class Logs extends Model
{
    const DATE_FORMAT = "Y-m-d H:i:s";

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $entity;

    /**
     *
     * @var integer
     */
    public $entityId;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var integer
     */
    public $userId;

    /**
     *
     * @var string
     */
    public $action;

    /**
     *
     * @var string
     */
    public $diff;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'logs';
    }

    public function beforeSave()
    {
        $this->diff = json_encode($this->diff);
    }

    public function afterSave()
    {
        $this->diff = json_decode($this->diff);
    }

    public function afterFetch()
    {
        $this->diff = json_decode($this->diff);
    }

    public function validation()
    {
        return $this->validate(new LogsValidation());
    }

    public function getResultsetClass()
    {
        return 'LogsResultset';
    }
}

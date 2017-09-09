<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class LogsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'logs',
            array(
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 32,
                            'first' => true
                        )
                    ),
                    new Column(
                        'entity',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'entityId',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 32,
                            'after' => 'entity'
                        )
                    ),
                    new Column(
                        'date',
                        array(
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'entityId'
                        )
                    ),
                    new Column(
                        'userId',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 32,
                            'after' => 'date'
                        )
                    ),
                    new Column(
                        'action',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 40,
                            'after' => 'userId'
                        )
                    ),
                    new Column(
                        'diff',
                        array(
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'action'
                        )
                    )
                ),
                'indexes' => array(
                    new Index(
                        "PRIMARY",
                        array("id")
                    ),
                ),
            )
        );
    }
}

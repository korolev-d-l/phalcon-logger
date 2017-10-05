<?php

namespace Helper;

use Codeception\Module;
use Faker\Factory as Faker;
use Logs;

/**
 * Log Helper
 *
 * Here you can define custom actions
 * all public methods declared in helper class will be available in $I
 *
 * @package Helper
 */
class Log extends Module
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var \Codeception\Module\Phalcon
     */
    protected $phalcon;

    /**
     * Triggered after module is created and configuration is loaded
     */
    public function _initialize()
    {
        $this->faker = Faker::create();
        $this->phalcon = $this->getModule('Phalcon');
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function generateAttributes(array $attributes = [])
    {
        $attributes = $attributes ?: [];

        return array_merge_recursive([
            'entity'   => $this->faker->text(),
            'entityId' => $this->faker->numberBetween(),
            'date'     => $this->faker->date(Logs::DATE_FORMAT),
            'userId'   => $this->faker->numberBetween(),
            'action'   => $this->faker->randomElement(["create", "edit", "delete"]),
            'diff'     => [
                'before' => $this->faker->text(),
                'after' => $this->faker->text()
            ]
        ], $attributes);
    }

    /**
     * Creates a random logs and return its id
     *
     * @param array $attributes Model attributes [Optional]
     * @return int
     */
    public function haveLog(array $attributes = null)
    {
        $attributes = $this->generateAttributes($attributes);

        return $this->phalcon->haveRecord(Logs::class, $attributes);
    }
}
<?php

use Codeception\Test\Unit;

class LogsTest extends Unit
{
    /**
     * UnitTester Object
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Helper\Log
     */
    protected $log;

    public function _inject(\Helper\Unit $unit, \Helper\Log $log)
    {
        $this->log = $log;
    }

    public function testSaveTrue()
    {
        $logsModel = new Logs();
        $logsModel->assign($this->log->generateAttributes());
        $this->assertTrue($logsModel->save());
    }

    public function testSaveFalse()
    {
        $logsModel = new Logs();
        $logsModel->assign($this->log->generateAttributes([
            'date' => null,
            'diff' => null
        ]));
        $this->assertFalse($logsModel->save());
    }

    public function testGetResultset()
    {
        $logsModel = new Logs();
        $this->assertEquals('LogsResultset', $logsModel->getResultsetClass());
    }
}
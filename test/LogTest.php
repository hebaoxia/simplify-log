<?php
/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-09-20 at 23:12:38.
 */
require_once 'base/TestBase.php';
class LogTest extends BaseTest
{
    /**
     * @var Log
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Log::getLogger();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Log::getLogger
     * @author mr2longly
     */
    public function testGetLogger()
    {
        $logger = Log::getLogger('myLogger');
        $this->assertTrue($logger instanceof Log);
    }

    /**
     * @covers Log::turnOn
     * @author mr2longly
     */
    public function testTurnOn()
    {
        $this->object->turnOn(Config::L_DEBUG);
        $this->assertTrue($this->object->on(Config::L_DEBUG));
    }

    /**
     * @covers Log::turnOff
     * @author mr2longly
     */
    public function testTurnOff()
    {
        $this->object->turnOff(Config::L_DEBUG);
        $this->assertFalse($this->object->on(Config::L_DEBUG));
    }

    /**
     * @covers Log::on
     * @author mr2longly
     */
    public function testOn()
    {
        $this->object->turnOn(Config::L_DEBUG);
        $this->assertTrue($this->object->on(Config::L_DEBUG));
    }

    /**
     * @covers Log::fatal
     * @author mr2longly
     */
    public function testFatal()
    {
        $this->assertFalse($this->object->turnOff(Config::L_FATAL)->fatal('this is Log::fatal test fail'));
        $this->assertTrue($this->object->turnOn(Config::L_FATAL)->fatal('this is Log::fatal test sucess'));
    }

    /**
     * @covers Log::error
     * @author mr2longly
     */
    public function testError()
    {
        $this->assertFalse($this->object->turnOff(Config::L_ERROR)->error('this is Log::error test fail'));
        $this->assertTrue($this->object->turnOn(Config::L_ERROR)->error('this is Log::error test sucess'));
    }

    /**
     * @covers Log::exception
     * @author mr2longly
     */
    public function testException()
    {
        try {
            throw new Exception('this is Log::exception test success');
        } catch (Exception $e) {
            $this->assertFalse($this->object->turnOff(Config::L_EXCEPTION)->exception($e));
            $this->assertTrue($this->object->turnOn(Config::L_EXCEPTION)->exception($e));
        }
    }

    /**
     * @covers Log::abnormal
     * @author mr2longly
     */
    public function testAbnormal()
    {
        $this->assertFalse($this->object->turnOff(Config::L_ABNORMAL)->abnormal('this is Log::abnormal test fail'));
        $this->assertTrue($this->object->turnOn(Config::L_ABNORMAL)->abnormal('this is Log::abnormal test sucess'));
    }

    /**
     * @covers Log::runtime
     * @author mr2longly
     */
    public function testRuntime()
    {
        $this->assertFalse($this->object->turnOff(Config::L_RUNTIME)->runtime('this is Log::runtime test fail'));
        $this->assertTrue($this->object->turnOn(Config::L_RUNTIME)->runtime('this is Log::runtime test sucess'));
    }

    /**
     * @covers Log::warning
     * @author mr2longly
     */
    public function testWarning()
    {
        $this->assertFalse($this->object->turnOff(Config::L_WARNING)->warning('this is Log::warning test fail'));
        $this->assertTrue($this->object->turnOn(Config::L_WARNING)->warning('this is Log::warning test sucess'));
    }

    /**
     * @covers Log::unknow
     * @author mr2longly
     */
    public function testUnknow()
    {
        $this->assertFalse($this->object->turnOff(Config::L_UNKNOW)->unknow('this is Log::unknow test fail'));
        $this->assertTrue($this->object->turnOn(Config::L_UNKNOW)->unknow('this is Log::unknow test sucess'));
    }

    /**
     * @covers Log::debug
     * @author mr2longly
     */
    public function testDebug()
    {
        $this->assertFalse($this->object->turnOff(Config::L_DEBUG)->debug('this is Log::debug test fail'));
        $this->assertTrue($this->object->turnOn(Config::L_DEBUG)->debug('this is Log::debug test sucess'));
    }

    /**
     * @covers Log::info
     * @author mr2longly
     */
    public function testInfo()
    {
        $this->assertFalse($this->object->turnOff(Config::L_INFO)->info('this is Log::info test fail'));
        $this->assertTrue($this->object->turnOn(Config::L_INFO)->info('this is Log::info test sucess'));
    }
}
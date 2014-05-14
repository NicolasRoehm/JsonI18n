<?php

namespace Tests;

use JsonI18n\DateFormat;

require_once dirname(__FILE__) . '/../../lib/DateFormat.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-03-25 at 06:06:34.
 */
class DateFormatTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var DateFormat
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        date_default_timezone_set('America/Toronto');
        $this->object = new DateFormat('fr-CA');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     * @covers JsonI18n\DateFormat::__construct
     */
    public function testDefaultConstructor() {
        $obj2 = new DateFormat('en-CA');
        
        $this->assertEquals(\PHPUnit_Framework_Assert::readAttribute($obj2, 'locale'), 'en-CA');
    }
    
    /**
     * @covers JsonI18n\DateFormat::__construct
     * @expectedException \PHPUnit_Framework_Error_Warning
     */
    public function testConstructorFail() {
        new DateFormat();
    }
    
    /**
     * @covers JsonI18n\DateFormat::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid locale.
     */
    public function testConstructorInvalidLanguage() {
        new DateFormat('');
    }

    /**
     * @covers JsonI18n\DateFormat::addResource
     * @covers JsonI18n\DateFormat::processData
     */
    public function testAddResource() {
        $this->object->addResource(__DIR__ . '/../resources/dateformats.json');
        
        $ref = \PHPUnit_Framework_Assert::readAttribute($this->object, 'formatters');
        
        $this->assertArrayHasKey('day_date', $ref['en-CA']);
        $this->assertArrayHasKey('day_date_time', $ref['fr-CA']);
        
        $this->assertInstanceOf('IntlDateFormatter', $ref['en-CA']['day_date']);
        $this->assertInstanceOf('IntlDateFormatter', $ref['fr-CA']['day_date_time']);
        
        $this->assertEquals($ref['en-CA']['day_date']->getPattern(), 'eeee d MMMM yyyy');
        $this->assertEquals($ref['fr-CA']['day_date_time']->getPattern(), 'EEE d MMM, yyyy à HH:mm');
    }
    
    /**
     * @covers JsonI18n\DateFormat::format()
     */
    public function testFormat() {
        $this->object->addResource(__DIR__ . '/../resources/dateformats.json');
        
        $date = new \DateTime('2014-01-01');
        $date2 = \DateTime::createFromFormat('Y-m-d H:i:s', '2014-07-01 08:00:00');
        
        $this->assertEquals($this->object->format($date, 'day', 'en-CA'), 'Wednesday');
        $this->assertEquals($this->object->format($date, 'day', 'fr-CA'), 'mercredi');
        
        $this->assertEquals($this->object->format($date2, 'day', 'en-CA'), 'Tuesday');
        $this->assertEquals($this->object->format($date2, 'day', 'fr-CA'), 'mardi');
        
        $this->assertEquals($this->object->format('2014-03-02', 'day', 'en-CA'), 'Sunday');
        $this->assertEquals($this->object->format('2014-03-02', 'day', 'fr-CA'), 'dimanche');
        
    }
    
    /**
     * @covers JsonI18n\DateFormat::format()
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Locale data not found.
     */
    public function testFormatInvalidLocale() {
        $this->object->addResource(__DIR__ . '/../resources/dateformats.json');
        
        $this->object->format('2014-03-02', 'day', 'zh-CN');
    }
    
    /**
     * @covers JsonI18n\DateFormat::format()
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Formatter not found for specified locale.
     */
    public function testFormatInvalidFormat() {
        $this->object->addResource(__DIR__ . '/../resources/dateformats.json');
        
        $this->object->format('2014-03-02', 'invalid');
    }
    
    /**
     * @covers JsonI18n\DateFormat::getFormatter()
     */
    public function testGetFormatter() {
        $this->object->addResource(__DIR__ . '/../resources/dateformats.json');
        
        $this->assertInstanceOf('IntlDateFormatter', $this->object->getFormatter('day'));
        $this->assertEquals($this->object->getFormatter('day')->getPattern(), 'cccc');
    }
    
    /**
     * @covers JsonI18n\DateFormat::getFormatter()
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Locale data not found.
     */
    public function testGetFormatterInvalidLocale() {
        $this->object->addResource(__DIR__ . '/../resources/dateformats.json');
        
        $this->object->getFormatter('day', 'zh-CN');
    }
    
    /**
     * @covers JsonI18n\DateFormat::getFormatter()
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Formatter not found for specified locale.
     */
    public function testGetFormatterInvalidFormatter() {
        $this->object->addResource(__DIR__ . '/../resources/dateformats.json');
        
        $this->object->getFormatter('invalid');
    }

}
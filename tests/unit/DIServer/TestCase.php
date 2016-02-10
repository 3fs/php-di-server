<?php
namespace trifs\DIServer\Tests\Unit;

use \trifs\DI\Container;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \trifs\DI\Container
     */
    protected $app;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->app = new Container;
        parent::setUp();
    }
}

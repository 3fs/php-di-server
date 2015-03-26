<?php
namespace trifs\DIServer\Tests\Unit;

use \trifs\DI\Container;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \trifs\DI\Container
     */
    protected $di;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->di = new Container;

        parent::setUp();
    }
}

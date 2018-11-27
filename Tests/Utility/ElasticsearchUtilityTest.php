<?php

namespace Tests\Chaplean\Bundle\ElasticsearchBundle\Utility;

use Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility;
use Elasticsearch\Client;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class ElasticsearchUtilityTest.
 *
 * @package   Tests\Chaplean\Bundle\ElasticsearchBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class ElasticsearchUtilityTest extends MockeryTestCase
{
    /** @var \Mockery\MockInterface */
    private $client;

    /** @var ElasticsearchUtility */
    private $elasticsearchUtility;

    /** @var \Closure */
    private $setClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setClient = function (Client $client) {
            $this->client = $client;
        };

        $this->elasticsearchUtility = new ElasticsearchUtility('localhost:1', ['foo' => 'bar']);
        $this->client = \Mockery::mock(Client::class);

        $this->setClient->call($this->elasticsearchUtility, $this->client);
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::__construct
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::buildScript
     *
     * @return void
     */
    public function testBuildScriptSimpleString(): void
    {
        $script = $this->elasticsearchUtility->buildScript([
            'foo = bar'
        ]);

        $this->assertSame('foo = bar', $script);
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::__construct
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::buildScript
     *
     * @return void
     */
    public function testBuildScriptMultipleString(): void
    {
        $script = $this->elasticsearchUtility->buildScript([
            'foo = bar',
            'bar = foo',
        ]);

        $this->assertSame('foo = bar; bar = foo', $script);
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::__construct
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::buildScript
     *
     * @return void
     */
    public function testBuildScriptKeyValueString(): void
    {
        $script = $this->elasticsearchUtility->buildScript([
            'foo' => 'bar'
        ]);

        $this->assertSame('ctx._source.foo = "bar"', $script);
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::__construct
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::buildScript
     *
     * @return void
     */
    public function testBuildScriptKeyValueInt(): void
    {
        $script = $this->elasticsearchUtility->buildScript([
            'foo' => 0
        ]);

        $this->assertSame('ctx._source.foo = 0', $script);
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::__construct
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::buildScript
     *
     * @return void
     */
    public function testBuildScriptKeyValueBoolean(): void
    {
        $script = $this->elasticsearchUtility->buildScript([
            'foo' => true
        ]);

        $this->assertSame('ctx._source.foo = true', $script);

        $script = $this->elasticsearchUtility->buildScript([
            'foo' => false
        ]);

        $this->assertSame('ctx._source.foo = false', $script);
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::__construct
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::buildScript
     *
     * @return void
     */
    public function testBuildScriptMeltingPotValues(): void
    {
        $script = $this->elasticsearchUtility->buildScript([
            'foo'    => false,
            'bar'    => 'foo',
            'foobar = 1',
            'barfoo' => 5
        ]);

        $this->assertSame('ctx._source.foo = false; ctx._source.bar = "foo"; foobar = 1; ctx._source.barfoo = 5', $script);
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::__construct
     * @covers \Chaplean\Bundle\ElasticsearchBundle\Utility\ElasticsearchUtility::updateByQuery
     *
     * @return void
     */
    public function testUpdateByQuery(): void
    {
        $this->client->shouldReceive('updateByQuery')->once()->with([
            'index'     => 'bar',
            'type'      => 'bar',
            'conflicts' => 'proceed',
            'body'      => [
                'query'  => [],
                'script' => [
                    'inline' => 'f = b'
                ]
            ]
        ])->andReturn([]);

        $this->elasticsearchUtility->updateByQuery('foo', 'bar', [], ['f = b']);
    }
}

<?php

namespace Tests\Chaplean\Bundle\ElasticsearchBundle\DependencyInjection;

use Chaplean\Bundle\ElasticsearchBundle\DependencyInjection\ChapleanElasticsearchExtension;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ChapleanElasticsearchExtensionTest.
 *
 * @package   Tests\Chaplean\Bundle\ElasticsearchBundle\DependencyInjection
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class ChapleanElasticsearchExtensionTest extends MockeryTestCase
{
    /** @var \Mockery\MockInterface|ContainerBuilder */
    private $container;

    /** @var ChapleanElasticsearchExtension */
    private $extension;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->container = new ContainerBuilder();
        $this->extension = new ChapleanElasticsearchExtension();
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\DependencyInjection\ChapleanElasticsearchExtension::load
     * @covers \Chaplean\Bundle\ElasticsearchBundle\DependencyInjection\ChapleanElasticsearchExtension::setParameters
     *
     * @return void
     */
    public function testLoadEmptyConfig(): void
    {
        $this->extension->load([], $this->container);

        $parameters = $this->container->getParameterBag();
        $this->assertTrue($parameters->has('chaplean_elasticsearch'));
        $this->assertSame(['indexes' => []], $parameters->get('chaplean_elasticsearch'));
        $this->assertTrue($parameters->has('chaplean_elasticsearch.indexes'));
        $this->assertSame([], $parameters->get('chaplean_elasticsearch.indexes'));
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\DependencyInjection\ChapleanElasticsearchExtension::load
     * @covers \Chaplean\Bundle\ElasticsearchBundle\DependencyInjection\ChapleanElasticsearchExtension::setParameters
     *
     * @return void
     */
    public function testLoadConfig(): void
    {
        $this->extension->load(
            [
                'chaplean_elasticsearch' => [
                    'indexes' => [
                        'foo' => 'bar'
                    ]
                ]
            ],
            $this->container
        );

        $parameters = $this->container->getParameterBag();
        $this->assertTrue($parameters->has('chaplean_elasticsearch'));
        $this->assertSame(
            [
                'indexes' => [
                    'foo' => 'bar'
                ]
            ],
            $parameters->get('chaplean_elasticsearch')
        );

        $this->assertTrue($parameters->has('chaplean_elasticsearch.indexes'));
        $this->assertSame(
            [
                'foo' => 'bar'
            ],
            $parameters->get('chaplean_elasticsearch.indexes')
        );

        $this->assertTrue($parameters->has('chaplean_elasticsearch.indexes.foo'));
        $this->assertSame('bar', $parameters->get('chaplean_elasticsearch.indexes.foo'));
    }
}

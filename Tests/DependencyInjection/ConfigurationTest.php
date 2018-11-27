<?php

namespace Tests\Chaplean\Bundle\ElasticsearchBundle\DependencyInjection;

use Chaplean\Bundle\ElasticsearchBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class ConfigurationTest.
 *
 * @package   Tests\Chaplean\Bundle\ElasticsearchBundle\DependencyInjection
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class ConfigurationTest extends TestCase
{
    /** @var Configuration */
    private $configuration;
    /** @var Processor */
    private $processor;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     *
     * @return void
     */
    public function testBuildEmptyConfig(): void
    {
        $config = $this->processor->processConfiguration($this->configuration, []);

        $this->assertSame(['indexes' => []], $config);
    }

    /**
     * @covers \Chaplean\Bundle\ElasticsearchBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     *
     * @return void
     */
    public function testGetConfigTreeBuilder(): void
    {
        $config = $this->processor->processConfiguration(
            $this->configuration,
            [
                'chaplean_elasticsearch' => [
                    'indexes' => [
                        'foo' => 'bar',
                        'bar' => 'foo'
                    ]
                ]
            ]
        );

        $this->assertSame(
            [
                'indexes' => [
                    'foo' => 'bar',
                    'bar' => 'foo'
                ]
            ],
            $config
        );
    }
}

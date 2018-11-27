<?php

namespace Chaplean\Bundle\ElasticsearchBundle\Utility;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

/**
 * Class ElasticsearchUtility.
 *
 * @package   Chaplean\Bundle\ElasticsearchBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class ElasticsearchUtility
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $indexes;

    /**
     * ElasticSearchUtility constructor.
     *
     * @param string $host
     * @param array  $indexes
     */
    public function __construct(string $host, array $indexes)
    {
        $this->client = ClientBuilder::create()->setHosts([$host])->build();
        $this->indexes = $indexes;
    }

    /**
     * Updates documents matched by $query using $updateValues as a list of fields to new values.
     *
     * @param string $index         Key of the index to search in (as described in the elasticsearch.yml file)
     * @param string $type          Name of the document type to search in
     * @param array  $query         List of document ids to update
     * @param array  $updatedValues Associative array of keys to change to new values to set
     *
     * @return array
     */
    public function updateByQuery(string $index, string $type, array $query, array $updatedValues): array
    {
        $index = $this->indexes[$index];
        $script = $this->buildScript($updatedValues);

        return $this->client->updateByQuery(
            [
                'index'     => $index,
                'type'      => $type,
                'conflicts' => 'proceed',
                'body'      => [
                    'query'  => $query,
                    'script' => [
                        'inline' => $script
                    ]
                ]
            ]
        );
    }

    /**
     * @param array $updatedValues
     *
     * @return array
     */
    public function buildScript(array $updatedValues)
    {
        $script = [];

        foreach ($updatedValues as $key => $value) {
            if (is_string($key)) {
                if (!is_int($value) && !is_bool($value)) {
                    $value = '"' . $value . '"';
                } elseif (is_bool($value)) {
                    $value = ($value) ? 'true' : 'false';
                }

                $script[] = 'ctx._source.' . $key . ' = ' . $value;
            } else {
                $script[] = $value;
            }
        }

        return implode('; ', $script);
    }
}

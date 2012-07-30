<?php

/**
 * @see Zend_Paginator_Adapter_Interface
 */
require_once 'Zend/Paginator/Adapter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Paginator
 */
class GlassOnion_Paginator_Adapter_SolrQuery
    implements Zend_Paginator_Adapter_Interface
{
    /**
     * @var SolrQuery
     */
    private $query;

    /**
     * @var SolrClient
     */
    private $client;

    /**
     * @var SolrClient
     */
    private $count = NULL;

    /**
     * Constructor
     *
     * @param SolrQuery $query
     * @param SolrClient|array $client
     * @return void
     */
    public function __construct(SolrQuery $query, $client)
    {
        $this->query = $query;

        // http://www.php.net/manual/en/solrclient.construct.php
        $this->client = $client instanceof SolrClient ? $client : new SolrClient($client);
    }

    /**
     * Returns the total number of items in the result
     *
     * @return integer
     */
    public function count()
    {
        if (NULL === $this->count) {
            $this->count = (int) $this->query($this->query->setRows(0))->numFound;
        }
        return $this->count;
    }

    /**
     * Returns an array of items for a page
     *
     * @param integer $offset Page offset
     * @param integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->query($this->query->setStart($offset)->setRows($itemCountPerPage))->docs;
    }

    /**
     * Performs a Solr query using the assigned client and returns the response
     *
     * @param SolrQuery $query
     * @return SolrObject
     */
    private function query(SolrQuery $query)
    {
        return $this->client->query($query)->getResponse()->response;
    }
}

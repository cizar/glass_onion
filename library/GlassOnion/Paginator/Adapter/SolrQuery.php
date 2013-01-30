<?php

/**
 * Glass Onion
 *
 * Copyright (c) 2009 César Kästli (cesarkastli@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the
 * Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @copyright  Copyright (c) 2009 César Kästli (cesarkastli@gmail.com)
 * @license    MIT
 */

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

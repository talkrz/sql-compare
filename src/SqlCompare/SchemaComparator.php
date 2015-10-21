<?php

namespace SqlCompare;

use Doctrine\DBAL\Schema\Schema,
    Doctrine\DBAL\Configuration,
    Doctrine\DBAL\Connection,
    Doctrine\DBAL\Schema\Comparator,
    Doctrine\DBAL\DriverManager;

class SchemaComparator
{
    /**
     * Configuration for Doctrine DBAL
     * @var \Doctrine\DBAL\Configuration
     */
    private $config;

    /**
     * First database url
     * @var string
     */
    private $leftDbUrl;

    /**
     * Second database url
     * @var string
     */
    private $rightDbUrl;

    /**
     * Comparator is built by providing two databases URLs
     *
     * @param string $leftDbUrl  value allowed by Doctrine DBAL
     * @param string $rightDbUrl value allowed by Doctrine DBAL
     */
    public function __construct($leftDbUrl, $rightDbUrl)
    {
        $this->config = new Configuration();
        $this->leftDbUrl = $leftDbUrl;
        $this->rightDbUrl = $rightDbUrl;
    }

    /**
     * Get SQLs to convert from left to right database
     * Convention is tool returns queries to convert left db to right one,
     * so queries are obtained based on left database platform
     *
     * @return array
     */
    public function diff()
    {
        $leftConnection = $this->createConnection($this->leftDbUrl);
        $rightConnection = $this->createConnection($this->rightDbUrl);
        $leftSchema = $leftConnection->getSchemaManager()
            ->createSchema();
        $rightSchema = $rightConnection->getSchemaManager()
            ->createSchema();

        $comparator = new Comparator();
        $schemaDiff = $comparator->compare($leftSchema, $rightSchema);

        $leftPlatform = $leftConnection->getDriver()
            ->getDatabasePlatform();

        return $schemaDiff->toSql($leftPlatform);
    }

    /**
     * Create Doctrine DBAL connection
     *
     * @param  string $dbUrl db url allowed for Doctrine DBAL
     * @return Connection
     */
    private function createConnection($dbUrl)
    {
        $connectionParams = array(
            'url' => $dbUrl,
        );
        return DriverManager::getConnection($connectionParams, $this->config);
    }
}

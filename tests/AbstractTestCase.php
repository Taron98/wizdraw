<?php

namespace Wizdraw\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;

/**
 * Class AbstractTestCase
 * @package Wizdraw\Tests
 */
abstract class AbstractTestCase extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions, CreatesApplication;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Assert that a given where condition exists in the database
     *
     * @param string $table
     * @param array $data
     * @param null $connection
     *
     * @return TestCase|AbstractTestCase
     */
    protected function seeInDatabase($table, array $data, $connection = null): AbstractTestCase
    {
        return parent::seeInDatabase($table, array_key_snake_case($data), $connection);
    }

    /**
     * Assert that a given where condition does not exist in the database
     *
     * @param string $table
     * @param array $data
     * @param null $connection
     *
     * @return TestCase|AbstractTestCase
     */
    protected function notSeeInDatabase($table, array $data, $connection = null): AbstractTestCase
    {
        return parent::notSeeInDatabase($table, array_key_snake_case($data), $connection);
    }

}

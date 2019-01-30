<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerDirectoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerDirectoriesTable Test Case
 */
class CustomerDirectoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerDirectoriesTable
     */
    public $CustomerDirectories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.customer_directories',
        'app.firms',
        'app.customer_files'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerDirectories') ? [] : ['className' => CustomerDirectoriesTable::class];
        $this->CustomerDirectories = TableRegistry::getTableLocator()->get('CustomerDirectories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerDirectories);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

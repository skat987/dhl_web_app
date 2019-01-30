<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerFilesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerFilesTable Test Case
 */
class CustomerFilesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerFilesTable
     */
    public $CustomerFiles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.customer_files',
        'app.firms',
        'app.customer_directories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerFiles') ? [] : ['className' => CustomerFilesTable::class];
        $this->CustomerFiles = TableRegistry::getTableLocator()->get('CustomerFiles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerFiles);

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

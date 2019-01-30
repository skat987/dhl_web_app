<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FirmsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FirmsTable Test Case
 */
class FirmsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FirmsTable
     */
    public $Firms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.firms',
        'app.customer_directories',
        'app.customer_files',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Firms') ? [] : ['className' => FirmsTable::class];
        $this->Firms = TableRegistry::getTableLocator()->get('Firms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Firms);

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
}

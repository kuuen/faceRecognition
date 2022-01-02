<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FacesattendancesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FacesattendancesTable Test Case
 */
class FacesattendancesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FacesattendancesTable
     */
    protected $Facesattendances;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Facesattendances',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Facesattendances') ? [] : ['className' => FacesattendancesTable::class];
        $this->Facesattendances = $this->getTableLocator()->get('Facesattendances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Facesattendances);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FacesattendancesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\FacesattendancesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

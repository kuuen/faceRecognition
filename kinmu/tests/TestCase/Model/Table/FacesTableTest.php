<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FacesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FacesTable Test Case
 */
class FacesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FacesTable
     */
    protected $Faces;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Faces',
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
        $config = $this->getTableLocator()->exists('Faces') ? [] : ['className' => FacesTable::class];
        $this->Faces = $this->getTableLocator()->get('Faces', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Faces);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FacesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\FacesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

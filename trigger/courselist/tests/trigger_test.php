<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Trigger test for courselist trigger.
 *
 * @package    lifecycletrigger_courselist
 * @group      lifecycletrigger
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_lifecycle\trigger;

use tool_lifecycle\local\entity\trigger_subplugin;
use tool_lifecycle\processor;
use tool_lifecycle\local\response\trigger_response;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../lib.php');
require_once(__DIR__ . '/generator/lib.php');

/**
 * Trigger test for categories trigger.
 *
 * @package    lifecycletrigger_courselist
 * @group      lifecycletrigger
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_lifecycle_trigger_courselist_testcase extends \advanced_testcase {

    /** @var trigger_subplugin $excludetrigger Trigger instance that excludes a course by id. */
    private $excludebyidtrigger;
    /** @var trigger_subplugin $includetrigger Trigger instance that includes a course by id. */
    private $includebyidtrigger;
    /** @var trigger_subplugin $excludetrigger Trigger instance that excludes a course by shortname. */
    private $excludebyshortnametrigger;
    /** @var trigger_subplugin $includetrigger Trigger instance that includes a course by shortname. */
    private $includebyshortnametrigger;
    /** @var trigger_subplugin $excludetrigger Trigger instance that excludes a course by idnumber. */
    private $excludebyidnumbertrigger;
    /** @var trigger_subplugin $includetrigger Trigger instance that includes a course by idnumber. */
    private $includebyidnumbertrigger;

    /** @var processor $processor Instance of the lifecycle processor */
    private $processor;

    /**
     * Setup the testcase.
     * @throws \moodle_exception
     */
    public function setUp() {
        $this->resetAfterTest(true);
        $this->setAdminUser();

        $generator = $this->getDataGenerator();

        $this->processor = new processor();

        $category = $generator->create_category();
        $coursematch1 = $this->getDataGenerator()->create_course(array('category' => $category->id, 'idnumber' => 'TestMatchIDN1', 'shortname' => 'TestMatch1'));
        $coursematch2 = $this->getDataGenerator()->create_course(array('category' => $category->id, 'idnumber' => 'TestMatchIDN2', 'shortname' => 'TestMatch2'));
        $coursenomatch1 = $this->getDataGenerator()->create_course(array('category' => $category->id, 'idnumber' => 'TestNoMatchIDN1', 'shortname' => 'TestNoMatch1'));
        $coursenomatch2 = $this->getDataGenerator()->create_course(array('category' => $category->id, 'idnumber' => 'TestNoMatchIDN2', 'shortname' => 'TestNoMatch2'));

        $data = array(
            'courses' => "{$coursematch1->id},{$coursematch2->id}",
            'operation' => 'includecoursesbyid',
        );
        $this->excludebyidtrigger = \tool_lifecycle_trigger_categories_generator::create_trigger_with_workflow($data);

        $data['operation'] = 'excludecoursesbyid';
        $this->includebyidtrigger = \tool_lifecycle_trigger_categories_generator::create_trigger_with_workflow($data);

        $data = array(
            'courses' => "{$coursematch1->shortname},{$coursematch2->shortname}",
            'operation' => 'includecoursesbyshortname',
        );
        $this->includebyshortnametrigger = \tool_lifecycle_trigger_categories_generator::create_trigger_with_workflow($data);

        $data['operation'] = 'excludecoursesbyshortname';
        $this->excludebyshortnametrigger = \tool_lifecycle_trigger_categories_generator::create_trigger_with_workflow($data);

        $data = array(
            'courses' => "{$coursematch1->idnumber},{$coursematch2->idnumber}",
            'operation' => 'includecoursesbyidnumber',
        );
        $this->includebyidnumbertrigger = \tool_lifecycle_trigger_categories_generator::create_trigger_with_workflow($data);

        $data['operation'] = 'excludecoursesbyidnumber';
        $this->excludebyidnumbertrigger = \tool_lifecycle_trigger_categories_generator::create_trigger_with_workflow($data);
    }

    /**
     * Tests if courses, which are in the category are correctly triggered.
     */
    public function test_courses_in_list() {

        $recordset = $this->processor->get_course_recordset([$this->excludebyidtrigger], []);

        $this->assertTrue(!in_array($coursematch1->id, array_keys($recordset)), 'The course match 1 should have been excluded');
        $this->assertTrue(!in_array($coursematch2->id, array_keys($recordset)), 'The course match 2 should have been excluded');
        $this->assertTrue(in_array($coursenotmatch1->id, array_keys($recordset)), 'The course not match 1 should have been triggered');
        $this->assertTrue(in_array($coursenotmatch2->id, array_keys($recordset)), 'The course not match 2 should have been triggered');

        $recordset->close();

        $recordset = $this->processor->get_course_recordset([$this->includebyidtrigger], []);

        $this->assertTrue(in_array($coursematch1->id, array_keys($recordset)), 'The course match 1 should have been triggered');
        $this->assertTrue(in_array($coursematch2->id, array_keys($recordset)), 'The course match 2 should have been triggered');
        $this->assertTrue(!in_array($coursenotmatch1->id, array_keys($recordset)), 'The course not match 1 should have been excluded');
        $this->assertTrue(!in_array($coursenotmatch2->id, array_keys($recordset)), 'The course not match 2 should have been excluded');

        $recordset->close();

        $recordset = $this->processor->get_course_recordset([$this->excludebyshortnametrigger], []);

        $this->assertTrue(!in_array($coursematch1->id, array_keys($recordset)), 'The course match 1 should have been excluded');
        $this->assertTrue(!in_array($coursematch2->id, array_keys($recordset)), 'The course match 2 should have been excluded');
        $this->assertTrue(in_array($coursenotmatch1->id, array_keys($recordset)), 'The course not match 1 should have been triggered');
        $this->assertTrue(in_array($coursenotmatch2->id, array_keys($recordset)), 'The course not match 2 should have been triggered');

        $recordset->close();

        $recordset = $this->processor->get_course_recordset([$this->includebyshortnametrigger], []);

        $this->assertTrue(in_array($coursematch1->id, array_keys($recordset)), 'The course match 1 should have been triggered');
        $this->assertTrue(in_array($coursematch2->id, array_keys($recordset)), 'The course match 2 should have been triggered');
        $this->assertTrue(!in_array($coursenotmatch1->id, array_keys($recordset)), 'The course not match 1 should have been excluded');
        $this->assertTrue(!in_array($coursenotmatch2->id, array_keys($recordset)), 'The course not match 2 should have been excluded');

        $recordset->close();

        $recordset = $this->processor->get_course_recordset([$this->excludebyidnumbertrigger], []);

        $this->assertTrue(!in_array($coursematch1->id, array_keys($recordset)), 'The course match 1 should have been excluded');
        $this->assertTrue(!in_array($coursematch2->id, array_keys($recordset)), 'The course match 2 should have been excluded');
        $this->assertTrue(in_array($coursenotmatch1->id, array_keys($recordset)), 'The course not match 1 should have been triggered');
        $this->assertTrue(in_array($coursenotmatch2->id, array_keys($recordset)), 'The course not match 2 should have been triggered');

        $recordset->close();

        $recordset = $this->processor->get_course_recordset([$this->includebyidnumbertrigger], []);

        $this->assertTrue(in_array($coursematch1->id, array_keys($recordset)), 'The course match 1 should have been triggered');
        $this->assertTrue(in_array($coursematch2->id, array_keys($recordset)), 'The course match 2 should have been triggered');
        $this->assertTrue(!in_array($coursenotmatch1->id, array_keys($recordset)), 'The course not match 1 should have been excluded');
        $this->assertTrue(!in_array($coursenotmatch2->id, array_keys($recordset)), 'The course not match 2 should have been excluded');

        $recordset->close();
    }
}
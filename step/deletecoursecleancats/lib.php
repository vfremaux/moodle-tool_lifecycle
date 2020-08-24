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
 * Step subplugin to delete a course and categories.
 *
 * @package    lifecyclestep_deletecoursecleancats
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_lifecycle\step;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/admin/tool/lifecycle/step/lib.php');
require_once($CFG->dirroot.'/admin/tool/lifecycle/step/deletecoursecleancats/compatlib.php');

use tool_lifecycle\local\manager\settings_manager;
use tool_lifecycle\local\response\step_response;
use tool_lifecycle\settings_type;
use lifecysltstep_deletecoursecleancats\compat;

/**
 * Step subplugin to delete a course and categories if empty.
 *
 * @package    lifecyclestep_deletecoursecleancats
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class deletecoursecleancats extends libbase {

    /** @var int $numberofdeletions Deletions done so far in this php call. */
    private static $numberofdeletions = 0;

    /**
     * Processes the course and returns a repsonse.
     * The response tells either
     *  - that the subplugin is finished processing.
     *  - that the subplugin is not yet finished processing.
     *  - that a rollback for this course is necessary.
     * @param int $processid of the respective process.
     * @param int $instanceid of the step instance.
     * @param mixed $course to be processed.
     * @return step_response
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function process_course($processid, $instanceid, $course) {
        if (self::$numberofdeletions >= settings_manager::get_settings(
            $instanceid, settings_type::STEP)['maximumdeletionspercron']) {
            return step_response::waiting(); // Wait with further deletions til the next cron run.
        }

        delete_course($course->id, true);

        $category = compat::get_category($course->category);
        $this->clean_cats($category, $instanceid);

        self::$numberofdeletions++;

        return step_response::proceed();
    }

    /**
     * Processes the course in status waiting and returns a repsonse.
     * The response tells either
     *  - that the subplugin is finished processing.
     *  - that the subplugin is not yet finished processing.
     *  - that a rollback for this course is necessary.
     * @param int $processid of the respective process.
     * @param int $instanceid of the step instance.
     * @param mixed $course to be processed.
     * @return step_response
     */
    public function process_waiting_course($processid, $instanceid, $course) {
        return $this->process_course($processid, $instanceid, $course);
    }

    /**
     * The return value should be equivalent with the name of the subplugin folder.
     * @return string technical name of the subplugin
     */
    public function get_subpluginname() {
        return 'deletecoursecleancats';
    }

    /**
     * Defines which settings each instance of the subplugin offers for the user to define.
     * @return instance_setting[] containing settings keys and PARAM_TYPES
     */
    public function instance_settings() {
        return array(
            new instance_setting('maximumdeletionspercron', PARAM_INT),
            new instance_setting('recurseup', PARAM_BOOL),
        );
    }

    /**
     * This method can be overriden, to add form elements to the form_step_instance.
     * It is called in definition().
     * @param \MoodleQuickForm $mform
     * @throws \coding_exception
     */
    public function extend_add_instance_form_definition($mform) {

        $elementname = 'maximumdeletionspercron';
        $mform->addElement('text', $elementname, get_string('maximumdeletionspercron', 'lifecyclestep_deletecoursecleancats'));
        $mform->setType($elementname, PARAM_INT);
        $mform->setDefault($elementname, 10);

        $elementname = 'recurseup';
        $mform->addElement('text', $elementname, get_string('recurseup', 'lifecyclestep_deletecoursecleancats'));
        $mform->setType($elementname, PARAM_BOOL);
        $mform->setDefault($elementname, true);
    }

    /**
     * Deletes a category if really empty
     * @param coursecat $category
     * @param int $instanceid step instance id
     */
    protected function clean_cats($category, $instanceid) {

        $recurseup = settings_manager::get_settings($instanceid, settings_type::STEP)['recurseup'];

        if (compat::is_category_empty($category)) {
            $category->delete_full(false);
        }

        if ($recurseup) {
            $parent = $category->get_parent_coursecat();
            if ($parent) {
                $this->clean_cats($parent, $instanceid);
            }
        }
    }
}

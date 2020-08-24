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
 * lib for Reset Archive State Step
 *
 * @package lifecyclestep_resetarchivestate
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_lifecycle\step;

global $CFG;
require_once($CFG->dirroot.'/admin/tool/lifecycle/trigger/archived/lib.php');
require_once($CFG->dirroot.'/admin/tool/lifecycle/step/lib.php');

use StdClass;
use tool_lifecycle\local\response\step_response;
use tool_lifecycle\trigger\archived;

defined('MOODLE_INTERNAL') || die();

class resetarchivestate extends libbase {

    private static $resetcourses = 0;

    /**
     * Process course by marking the wait for pull signal. Courses will be collected
     * by the remote end calling to the request_pull_list web service.
     *
     * @param int $processid of the respective process.
     * @param int $instanceid of the step instance.
     * @param mixed $course to be processed.
     * @return step_response
     */
    public function process_course($processid, $instanceid, $course) {
        global $DB;

        $triggerid = settings_manager::get_settings($instanceid, settings_type::STEP)['triggerid'];
        $record = new StdClass();
        $record->triggerid = $triggerid;
        $record->courseid = $course->id;
        $record->status = archived::STATUS_STALLED;

        if ($oldrecord = $DB->get_record('lifecycletrigger_archived', $params)) {
            $record->id = $olderecord->id;
            $DB->update_record('lifecycletrigger_archived', $record);
        } else {
            $DB->insert_record('lifecycletrigger_archived', $record);
        }

        return step_response::waiting();
    }

    public function rollback_course($processid, $instanceid, $course) {
        global $DB;

        $triggerid = settings_manager::get_settings($instanceid, settings_type::STEP)['triggerid'];
        $params = ['triggerid' => $triggerid, 'courseid' => $course->id];
        $DB->delete_records('lifecycletrigger_archived', $params);
        return;
    }

    public function get_subpluginname() {
        return 'resetarchivestate';
    }

    public function process_waiting_course($processid, $instanceid, $course) {
        $this->process_courses($processid, $instanceid, $course);
    }

    public function pre_processing_bulk_operation() {
        self::$resetcourses = 0;
    }

    public function post_processing_bulk_operation() {
        global $CFG, $SITE;

        if (self::$resetcourses > 0) {
            email_to_user(get_admin(), \core_user::get_noreply_user(),
                get_string('emailsubject', 'lifecyclestep_resetarchivestate', $SITE->shortname),
                get_string('emailcontent', 'lifecyclestep_resetarchivestate', self::$resetcourses),
                get_string('emailcontenthtml', 'lifecyclestep_resetarchivestate', self::$resetcourses));
        }
    }

    public function instance_settings() {
        return array(
            new instance_setting('triggerid', PARAM_TEXT),
        );
    }

    public function extend_add_instance_form_definition($mform) {
        global $DB;

        $elementname = 'triggerid';

        $params = ['subpluginname' => 'archived'];
        $options = $DB->get_records_menu('tool_lifecycle_trigger', $params, 'id, instancename');

        if (empty($options)) {
            $mform->addElement('static', $elementname, get_string('needstriggerid', 'lifecyclestep_resetarchivestate'));
        } else {
            $mform->addElement('select', $elementname, get_string('triggerid', 'lifecyclestep_resetarchivestate'), $options);
            $mform->addHelpButton($elementname, 'triggerid', 'lifecyclestep_resetarchivestate');
            $mform->setType($elementname, PARAM_INT);
        }
    }
}

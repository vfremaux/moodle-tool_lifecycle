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
 * lib for Admin Approve Step
 *
 * @package lifecyclestep_adminapprove
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_lifecycle\step;

use tool_lifecycle\local\response\step_response;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../lib.php');

class adminapprove extends libbase {

    private static $newcourses = 0;

    /**
     * @param int $processid of the respective process.
     * @param int $instanceid of the step instance.
     * @param mixed $course to be processed.
     * @return step_response
     */
    public function process_course($processid, $instanceid, $course) {
        global $DB;
        $record = new \stdClass();
        $record->processid = $processid;
        $record->status = 0;
        $DB->insert_record('lifecyclestep_adminapprove', $record);
        self::$newcourses++;
        return step_response::waiting();
    }

    public function rollback_course($processid, $instanceid, $course) {
        global $DB;
        $DB->delete_records('lifecyclestep_adminapprove', array('processid' => $processid));
        return;
    }

    public function get_subpluginname() {
        return 'adminapprove';
    }

    public function process_waiting_course($processid, $instanceid, $course) {
        global $DB;
        $record = $DB->get_record('lifecyclestep_adminapprove', array('processid' => $processid));
        switch ($record->status) {
            case 1:
                $DB->delete_records('lifecyclestep_adminapprove', array('processid' => $processid));
                return step_response::proceed();
            case 2:
                $DB->delete_records('lifecyclestep_adminapprove', array('processid' => $processid));
                return step_response::rollback();
            default:
                return step_response::waiting();
        }
    }

    public function pre_processing_bulk_operation() {
        self::$newcourses = 0;
    }

    public function post_processing_bulk_operation() {
        global $CFG;
        if (self::$newcourses > 0) {
            $obj = new \stdClass();
            $obj->amount = self::$newcourses;
            $obj->url = $CFG->wwwroot . '/admin/tool/lifecycle/step/adminapprove/index.php';

            email_to_user(get_admin(), \core_user::get_noreply_user(),
                get_string('emailsubject', 'lifecyclestep_adminapprove'),
                get_string('emailcontent', 'lifecyclestep_adminapprove',  $obj),
                get_string('emailcontenthtml', 'lifecyclestep_adminapprove', $obj));
        }
    }

    public function instance_settings() {
        return array(
            new instance_setting('statusmessage', PARAM_TEXT),
        );
    }

    public function extend_add_instance_form_definition($mform) {
        $elementname = 'statusmessage';
        $mform->addElement('text', $elementname, get_string('statusmessage', 'lifecyclestep_adminapprove'));
        $mform->addHelpButton($elementname, 'statusmessage', 'lifecyclestep_adminapprove');
        $mform->setType($elementname, PARAM_TEXT);
    }
}

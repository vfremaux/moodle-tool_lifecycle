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
 * Subplugin for detecting the no activity courses based on last logs.
 *
 * @package lifecycletrigger_noactivitycourses
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_lifecycle\trigger;

use tool_lifecycle\local\manager\settings_manager;
use tool_lifecycle\local\response\trigger_response;
use tool_lifecycle\settings_type;

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/../lib.php');
require_once(__DIR__ . '/../../lib.php');

/**
 * Class which implements the basic methods necessary for a cleanyp courses trigger subplugin
 * @package lifecycletrigger_startdatedelay
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class noactivitycourses extends base_automatic {

    /**
     * Checks the course and returns a repsonse, which tells if the course should be further processed.
     * @param object $course Course to be processed.
     * @param int $triggerid Id of the trigger instance.
     * @return trigger_response
     */
    public function check_course($course, $triggerid) {
        // Everything is already in the sql statement.
        global $DB;

        // Get the last log in course for each user where log is over the horizon.
        $sql = "
            SELECT
                userid,
                MAX(timecreated)
            FROM
                {logstore_standard_log}
            WHERE
                courseid = ? AND
                timecreated > ?
            GROUP BY
                userid
        ";

        $delay = settings_manager::get_settings($triggerid, settings_type::TRIGGER)['delay'];

        $lastuserlogs = $DB->execute($sql, [$course->id, time() - $delay * DAYSECS]);

        $skiproles = settings_manager::get_settings($triggerid, settings_type::TRIGGER)['skiproles'];

        $coursecontext = context_course::instance($course->id);

        list($insql, $inparams) = $DB->get_in_or_equal($skiproles, SQL_PARAM_QM, 'param', false);

        foreach ($lastuserlogs as $userlog) {
            // check if user is not unchecked role.
            $sql = "
                SELECT
                    *
                FROM
                    {role_assignments} ra
                WHERE
                    ra.userid = ? AND
                    ra.contextid = ? AND
                    ra.roleid $insql
            ";

            $params = [$userlog->userid, $coursecontext->id];
            foreach ($inparams as $inparam) {
                $params[] = $inparam;
            }

            $relevantroles = $DB->execute($sql, $params);
            // The first record who has relevant roles triggers.

            if (!empty($relevantroles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add sql comparing the current date to the start date of a course in combination with the specified delay.
     * @param int $triggerid Id of the trigger.
     * @return array A list containing the constructed sql fragment and an array of parameters.
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_course_recordset_where($triggerid) {
        return array('true', []);
    }

    /**
     * The return value should be equivalent with the name of the subplugin folder.
     * @return string technical name of the subplugin
     */
    public function get_subpluginname() {
        return 'noactivitycourses';
    }

    /**
     * Defines which settings each instance of the subplugin offers for the user to define.
     * @return instance_setting[] containing settings keys and PARAM_TYPES
     */
    public function instance_settings() {
        return array(
            new instance_setting('delay', PARAM_INT),
            new instance_setting('skiproles', PARAM_SEQUENCE)
        );
    }

    /**
     * At the delay since the start date of a course.
     * @param \MoodleQuickForm $mform
     * @throws \coding_exception
     */
    public function extend_add_instance_form_definition($mform) {
        $mform->addElement('duration', 'delay', get_string('delay', 'lifecycletrigger_noactivitycourses'));
        $mform->addHelpButton('delay', 'delay', 'lifecycletrigger_noactivitycourses');

        // Standard roles.
        $teacherrole = $DB->get_field('roles', 'id', ['shortname' => 'teacher']);
        $editingteacherrole = $DB->get_field('roles', 'id', ['shortname' => 'editingteacher']);

        // Get all roles by localized name in system context.
        $roleoptions = role_get_names(null, ROLENAME_ALIAS, true);
        $formelm = & $mform->addElement('select', 'skiproles', get_string('delay', 'lifecycletrigger_noactivitycourses'), $roleoptions);
        $formelm->setMultiple(true);
        $mform->setType('skiproles', PARAM_SEQUENCE);
        $mform->setDefault([$teacherrole->id, $editingteacherrole->id]);
        $mform->addHelpButton('skiproles', 'skiproles', 'lifecycletrigger_noactivitycourses');
    }

    /**
     * Reset the delay at the add instance form initializiation.
     * @param \MoodleQuickForm $mform
     * @param array $settings array containing the settings from the db.
     */
    public function extend_add_instance_form_definition_after_data($mform, $settings) {
        if (is_array($settings) && array_key_exists('delay', $settings)) {
            $default = $settings['delay'];
        } else {
            $default = 16416000;
        }
        $mform->setDefault('delay', $default);
    }
}

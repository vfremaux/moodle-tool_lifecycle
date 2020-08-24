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
 * Trigger subplugin to include or exclude courses refered by an explicit courselist.
 *
 * @package lifecycletrigger_courselist
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_lifecycle\trigger;

use coursecat;
use tool_lifecycle\local\manager\settings_manager;
use tool_lifecycle\local\response\trigger_response;
use tool_lifecycle\settings_type;

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/../lib.php');
require_once(__DIR__ . '/../../lib.php');

/**
 * Class which implements the basic methods necessary for a cleanyp courses trigger subplugin
 * @package lifecycletrigger_categories
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class courselist extends base_automatic {

    /**
     * Checks the course and returns a response, which tells if the course should be further processed.
     * @param object $course Course to be processed.
     * @param int $triggerid Id of the trigger instance.
     * @return trigger_response
     */
    public function check_course($course, $triggerid) {
        // Every decision is already in the where statement.
        return trigger_response::trigger();
    }

    /**
     * Return sql sniplet for including (or excluding) the courses belonging to specific categories
     * and all their children.
     * @param int $triggerid Id of the trigger.
     * @return array A list containing the constructed sql fragment and an array of parameters.
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_course_recordset_where($triggerid) {
        global $DB, $CFG;

        $operation = settings_manager::get_settings($triggerid, settings_type::TRIGGER)['operation'];
        $courses = settings_manager::get_settings($triggerid, settings_type::TRIGGER)['courses'];

        $courseids = explode(',', $courses);

        switch ($operation) {
            case 'excludecoursesbyid' : {
                list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
                $where = "NOT {course}.id {$insql}";
                break;
            }

            case 'includecoursesbyid' : {
                list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
                $where = "{course}.id {$insql}";
                break;
            }

            case 'excludecoursesbyshortname' : {
                list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
                $where = "NOT {course}.shortname {$insql}";
                break;
            }

            case 'includecoursesbyshortname' : {
                list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
                $where = "{course}.shortname {$insql}";
                break;
            }

            case 'excludecoursesbyidnumber' : {
                list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
                $where = "NOT {course}.idnumber {$insql}";
                break;
            }

            case 'includecoursesbyidnumber' : {
                list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
                $where = "{course}.idnumber {$insql}";
                break;
            }
        }

        return array($where, $inparams);
    }

    /**
     * The return value should be equivalent with the name of the subplugin folder.
     * @return string technical name of the subplugin
     */
    public function get_subpluginname() {
        return 'categories';
    }

    /**
     * Defines which settings each instance of the subplugin offers for the user to define.
     * @return instance_setting[] containing settings keys and PARAM_TYPES
     */
    public function instance_settings() {
        return array(
            new instance_setting('courses', PARAM_TEXT),
            new instance_setting('operation', PARAM_ALPHA),
        );
    }

    /**
     * This method can be overriden, to add form elements to the form_step_instance.
     * It is called in definition().
     * @param \MoodleQuickForm $mform
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function extend_add_instance_form_definition($mform) {
        global $DB;

        $attrs = ' cols="80" rows="5" ';
        $mform->addElement('textarea', 'courses', get_string('courses', 'lifecycletrigger_courselist'), $attrs);
        $mform->setType('courses', PARAM_TEXT);

        $options = [
            'includecoursesbyid' => get_string('includecoursesbyid', 'lifecycletrigger_courselist'),
            'excludecoursesbyid' => get_string('excludecoursesbyid', 'lifecycletrigger_courselist'),
            'includecoursesbyshortname' => get_string('includecoursesbyshortname', 'lifecycletrigger_courselist'),
            'excludecoursesbyshortname' => get_string('excludecoursesbyshortname', 'lifecycletrigger_courselist'),
            'includecoursesbyidnumber' => get_string('includecoursesbyidnumber', 'lifecycletrigger_courselist'),
            'excludecoursesbyidnumber' => get_string('excludecoursesbyidnumber', 'lifecycletrigger_courselist'),
        ];
        $mform->addElement('select', 'operation', get_string('operation', 'lifecycletrigger_courselist'), $options);
        $mform->setType('operation', PARAM_ALPHA);
    }

}

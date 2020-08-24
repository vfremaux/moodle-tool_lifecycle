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
 * @package lifecycletrigger_archived
 * @category local
 * @author Valery Fremaux (valery.fremaux@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/externallib.php');
require_once($CFG->dirroot.'/admin/tool/lifecycle/trigger/archived/lib.php');

use tool_lifecycle\trigger\archived;

/**
 * Software environement wrappers
 *
 * This set of functions define wrappers to environmental usefull utilities
 * such fetching central configuration values or giving error feedback to environment
 *
 * Implementation of these fucntion assume central libs of the applciation are loaded
 * and full generic API is available.
 */

/**
 * Implement :
 *
 * get_archivable_courses()
 * update_course_status($cid, $status)
 */

/**
 * Archived Trigger external functions
 *
 * @package    lifecycletrigger_archived
 * @category   external
 * @copyright  2020 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class lifecycletrigger_archived_external extends external_api {

    /* source side Web Services */

    public static function get_archivable_courses_parameters() {
        return new external_function_parameters([
            'remotewwwroot' => new external_value(PARAM_TEXT, 'Archiver end point'),
            'triggerid' => new external_value(PARAM_INT, 'Specific trigger id or 0 for all')
        ]);
    }

    /**
     * retrieves the list of archivable courses for running an archive pull.
     * When the pull is finished, the archive operation will send a status update
     * to the source platform with the original status to perform callback local tasks on course.
     * @params bool $alldates true if all dates are required, even if not processable NOW.
     */
    public static function get_archivable_courses($remotewwwroot, $triggerid = 0) {
        global $DB;

        // Get concernend trigger ids. they must match remotewwwroot.
        $params = ['type' => 'trigger', 'name' => 'remotewwwroot', 'subplugin' => 'archived'];

        if (!$triggerid) {

            $sql = "
                SELECT
                    t.id,
                    t.instancename
                FROM
                    {tool_lifecycle_trigger} t,
                    {tool_lifecycle_settings} s
                WHERE
                    t.id = s.instanceid AND
                    s.type = :type AND
                    s.name = :name AND
                    t.subpluginname = :subplugin
            ";

            $triggers = $DB->get_records_sql($sql, $params);
            if ($triggers) {
                $triggerids = array_keys($triggers);
            }
        } else {
            $triggerids = [$triggerid];
        }

        if (empty($triggerids)) {
            return [];
        }

        list($insql, $inparams) = $DB->get_in_or_equal($triggerids);

        // Get course status and info for pull waiting courses.
        $sql = "
            SELECT
                c.id,
                c.shortname,
                c.fullname,
                c.idnumber,
                st.triggerid
            FROM
                {course} c,
                {lifecycletrigger_archived} st
            WHERE
                c.id = st.courseid AND
                st.status = ".archived::STATUS_WAITING_FOR_PULL." AND
                triggerid {$insql}
        ";

        $archivables = $DB->get_records_sql($sql, $inparams);
        if (empty($archivables)) {
            return [];
        }

        // Compute the full path category.
        foreach (array_keys($archivables) as $aid) {
            $catpathelms = [];
            // Get full categorypath for the course and add it to record.
            $cat = $DB->get_record('course_categories', ['id' => $a->category], 'id,parent,name');
            array_unshift($catpathelms, $cat->name);
            while ($parent = $cat->parent) {
                $cat = $DB->get_record('course_categories', ['id' => $a->category], 'id,parent,name');
                array_unshift($catpathelms, $cat->name);
            }
            $archivables[$aid]->sourcecategorypath = implode('/', $catpathelms);
        }

        $result = array_values($archivables);

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.2
     */
    public static function get_archivable_courses_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'course ID'),
                    'shortname' => new external_value(PARAM_TEXT, 'Course shortname'),
                    'fullname' => new external_value(PARAM_TEXT, 'Course fullname'),
                    'sourcecategorypath' => new external_value(PARAM_TEXT, 'Course full names category slashed path'),
                    'idnumber' => new external_value(PARAM_TEXT, 'Course idnumber'),
                    'triggerid' => new external_value(PARAM_INT, 'The trigger id holding the archiving status')
                )
            )
        );
    }

    // Course status

    /**
     * Get course status parameters
     */
    public static function update_course_status_parameters() {
        return new external_function_parameters(
            array(
                'courseidfield' => new external_value(PARAM_TEXT, 'course instance id field. Can be id, shortname or idnumber'),
                'courseid' => new external_value(PARAM_TEXT, 'Course id'),
                'triggerid' => new external_value(PARAM_INT, 'the trigger id'),
                'result' => new external_value(PARAM_BOOL, '0 for failed, 1 for success'),
            )
        );
    }

    /**
     * Updates the course status and processes locally to the postactions.
     * @param string $courseidfield
     * @param int|string $courseid
     * @param string $triggerid
     * @param string $result
     */
    public static function update_course_status($courseidfield, $courseid, $triggerid, $result) {
        global $DB, $USER;

        $course = self::validate_course_parameters(self::update_course_status_parameters(),
                        array(
                            'courseidfield' => $courseidfield,
                            'courseid' => $courseid,
                            'triggerid' => $triggerid,
                            'result' => $result
                        ));

        if (!$rec = $DB->get_record('tool_lifecycle_trigger', ['id' => $triggerid])) {
            throw new Exception("No trigger for the triggerid given.");
        }

        $params = ['courseid' => $courseid, 'triggerid' => $triggerid];
        $status = $DB->get_record('lifecycletrigger_archived', $params);

        if (!$status) {
            throw new Exception("No archive complete trigger status found for the triggerid/courseid given.");
        }

        if ($result) {
            $status->status = archived::STATUS_ARCHIVED;
        } else {
            $status->status = archived::STATUS_ARCHIVE_FAILURE;
        }
        $DB->update_record('lifecycletrigger_archived', $status);

        return true;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.2
     */
    public static function update_course_status_returns() {
        return new external_value(PARAM_BOOL, true);
    }

    protected static function validate_course_parameters($configparamdefs, $inputs) {
        global $DB;

        // Standard validation for input data types.
        $status = self::validate_parameters($configparamdefs, $inputs);

        if (!in_array($inputs['courseidfield'], ['id', 'shortname', 'idnumber'])) {
            throw new invalid_parameter_exception('Invalid field for course identity.');
        }

        switch ($inputs['courseidfield']) {
            case 'id' : {
                if (!$course = $DB->get_record('course', ['id' => $inputs['courseid']])) {
                    throw new invalid_parameter_exception('Course does not exist by id.');
                }
                return $course;
            }

            case 'shortname' : {
                if (!$course = $DB->get_record('course', ['shortname' => $inputs['courseid']])) {
                    throw new invalid_parameter_exception('Course does not exist by shortname.');
                }
                return $course;
            }

            case 'idnumber' : {
                if (!$course = $DB->get_record('course', ['idnumber' => $inputs['courseid']])) {
                    throw new invalid_parameter_exception('Course does not exist by idnumber.');
                }
                return $course;
            }
        }
    }

    /* Archive Side Web Services */

    public static function run_archive_parameters() {
        return new external_function_parameters([]);
    }

    public static function run_archive() {
        archived::task_pull_and_archive_courses();
        return true;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.2
     */
    public static function run_archive_returns() {
        return new external_value(PARAM_BOOL, true);
    }

}
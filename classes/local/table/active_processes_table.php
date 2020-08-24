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
 * Table listing active processes
 *
 * @package tool_lifecycle
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_lifecycle\local\table;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->dirroot.'/admin/tool/lifecycle/classes/local/table/lifecycle_table.php');

/**
 * Table listing active processes
 *
 * @package tool_lifecycle
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class active_processes_table extends lifecycle_table {

    /**
     * Constructor for active_processes_table.
     * @param int $uniqueid Unique id of this table.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        global $PAGE;
        $this->set_attribute('class', $this->attributes['class'] . ' ' . $uniqueid);
        $this->set_sql('c.id as courseid, ' .
            'c.fullname as coursefullname, ' .
            'c.shortname as courseshortname, ' .
            'instancename as instancename ',
            '{tool_lifecycle_process} p join ' .
            '{course} c on p.courseid = c.id join ' .
            '{tool_lifecycle_step} s '.
            'on p.workflowid = s.workflowid AND p.stepindex = s.sortindex',
            "TRUE");
        $this->define_baseurl($PAGE->url);
        $this->init();
    }

    /**
     * Initialize the table.
     */
    public function init() {
        $this->define_columns(['courseid', 'courseshortname', 'coursefullname', 'instancename']);
        $this->define_headers([
            get_string('course'),
            get_string('shortnamecourse'),
            get_string('fullnamecourse'),
            get_string('step', 'tool_lifecycle')]);
        $this->setup();
    }

    /**
     * Render courseid column.
     * @param object $row Row data.
     * @return string course link
     */
    public function col_courseid($row) {
        return \html_writer::link(course_get_url($row->courseid), $row->courseid);
    }

    /**
     * Render courseshortname column.
     * @param object $row Row data.
     * @return string course link
     */
    public function col_courseshortname($row) {
        return \html_writer::link(course_get_url($row->courseid), $row->courseshortname);
    }

    /**
     * Render coursefullname column.
     * @param object $row Row data.
     * @return string course link
     */
    public function col_coursefullname($row) {
        return \html_writer::link(course_get_url($row->courseid), format_string($row->coursefullname));
    }

    /**
     * Render instancename column.
     * @param object $row Row data.
     * @return string pluginname of the instance
     */
    public function col_instancename($row) {

        return $row->instancename;
    }
}

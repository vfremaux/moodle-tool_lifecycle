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
 * Life Cycle Admin Approve Step
 *
 * @package lifecyclestep_adminapprove
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace lifecyclestep_adminapprove;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

class course_filter_form extends \moodleform {

    protected function definition() {
        global $CFG;
        $mform = $this->_form;
        $mform->addElement('text', 'courseid', get_string('courseid', 'lifecyclestep_adminapprove'));
        $mform->setType('courseid', PARAM_ALPHANUM);
        $mform->addRule('courseid', get_string('only_number', 'lifecyclestep_adminapprove'), 'numeric', null, 'client');

        $mform->addElement('text', 'coursename', get_string('course'));
        $mform->setType('coursename', PARAM_NOTAGS);

        // Use core_course_category for moodle 3.6 and higher.
        if ($CFG->version >= 2018120300) {
            $categories = \core_course_category::get_all();
        } else {
            require_once($CFG->libdir . '/coursecatlib.php');
            $categories = \coursecat::get_all();
        }

        $categoryoptions = ['' => '-'];
        foreach ($categories as $category) {
            $categoryoptions[$category->id] = $category->name;
        }
        $mform->addElement('select', 'category', get_string('category'), $categoryoptions);

        $buttonarray = [
            $mform->createElement('submit', 'submitbutton', get_string('filter')),
            $mform->createElement('cancel'),
        ];
        $mform->addGroup($buttonarray, 'buttonar', '', [' '], false);
    }
}
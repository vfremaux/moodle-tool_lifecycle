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
 * Step subplugin to retire a course to another category.
 *
 * @package    lifecyclestep_retirecourse
 * @copyright  2020 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_lifecycle\step;

global $CFG;

require_once($CFG->dirroot.'/lib/coursecatlib.php');

class compat {

    public static function get_category_list($capability) {
        if (empty($capability)) {
            $capability = 'moodle/course:create';
        }
        $mycatlist = \coursecat::make_categories_list('moodle/course:create');
        return $mycatlist;
    }

}
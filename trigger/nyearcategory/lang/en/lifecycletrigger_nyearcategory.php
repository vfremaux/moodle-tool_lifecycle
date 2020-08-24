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
 * Lang strings for categories trigger
 *
 * @package lifecycletrigger_nyearcategory
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Nth Year Categories trigger';

$string['nyearcategory'] = 'Course from the Yearly session category identified by known idnumber pattern and millesim';
$string['configdefaultidnumberpattern'] = 'Default Year IdNumber Pattern';
$string['configdefaultidnumberpattern_desc'] = 'the IDNumber pattern set by default in all new instances. Uses %Y to insert year millesim.';
$string['configyeardateswitch'] = 'Date when the yearly session switches to "this year"';
$string['configyeardateswitch_desc'] = 'Use the MM.DD syntax';
$string['n'] = 'The N index of yearly category. <b>Take care</b> that the year date switch impacts the selection list.';
$string['exclude'] = 'If ticked, the named category is excluded from triggering instead.';

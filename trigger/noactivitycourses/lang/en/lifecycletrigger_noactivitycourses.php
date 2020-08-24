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
 * Lang strings for start date delay trigger
 *
 * @package    lifecycletrigger_noactivitycourses
 * @copyright  2020 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'No activity in course trigger';

$string['privacy:metadata'] = 'the No activity in course trigger does not hold any personal data.';

$string['delay'] = 'Delay of activity detection in logs';
$string['delay_help'] = 'The trigger will be invoked there is no user logs for this delay from the current time.';
$string['skiproles'] = 'Skipped roles';
$string['skiproles_help'] = 'some roles may NOT be checked for activity (i.e. whom activity is not relevant for saying is NOT an active course).';

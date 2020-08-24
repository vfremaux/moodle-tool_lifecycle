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
 * @package    lifecycletrigger_archived
 * @copyright  2020 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Start archive complete trigger';

$string['description'] = 'Start a process when the course has been remotely archived';
$string['description_help'] = 'the trigger will fire when the course is marked as having been sucessfully archived in a remote moodle.';

$string['remotewwwroot'] = 'Remote Archiver Root url';
$string['remotewwwroot_help'] = 'The wwwroot of the remote archiving moodle instance.';
$string['task_pull_and_archive'] = 'Examine remote archivable course list an pull courses in the current moodle.'; // @DYNA.
$string['configmaxcoursepullspercron'] = 'Max number of course pulls per cron';
$string['configmaxcoursepullspercron_desc'] = 'Tells how many course transfer process can be handled per cron process instance. Keep it sufficiantly low to let all other cron jobs running regularily.';

// Settings.
$string['configarchivesourcewwwroot'] = 'Archivable course source';
$string['configarchivesourcewwwroot_desc'] = 'A remote moodle platform (wwwroot) where from to get courses to archive';
$string['configarchivesourcetoken'] = 'source web service token';
$string['configarchivesourcetoken_desc'] = 'The remote web service token for the archive source';

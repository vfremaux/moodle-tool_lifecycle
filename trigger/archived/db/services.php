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
 * @package    lifecycletrigger_archived
 * @category   local
 * @author     Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$functions = array(

    'lifecycletrigger_archived_get_archivable_courses' => array(
        'classname' => 'lifecycletrigger_archived_external',
        'methodname' => 'get_archivable_courses',
        'classpath' => 'admin/tool/lifecycle/trigger/archived/externallib.php',
        'description' => 'Returns the list of archivable courses of the local moodle (archived side)',
        'type' => 'read',
    ),

    'lifecycletrigger_archived_update_course_status' => array(
        'classname' => 'lifecycletrigger_archived_external',
        'methodname' => 'update_course_status',
        'classpath' => 'admin/tool/lifecycle/trigger/archived/externallib.php',
        'description' => 'Updates local course state regarding archive process (archived side)',
        'type' => 'write',
    ),

    'lifecycletrigger_archived_run_archive' => array(
        'classname' => 'lifecycletrigger_archived_external',
        'methodname' => 'run_archive',
        'classpath' => 'admin/tool/lifecycle/trigger/archived/externallib.php',
        'description' => 'Run the archive pulling process (archiver side)',
        'type' => 'write',
    ),

);

$services = array(
    'Lifecycle Archivable Moodle API' => array(
        'functions' => array (
            'lifecycletrigger_archived_update_course_status',
            'lifecycletrigger_archived_get_archivable_courses',
            'lifecycletrigger_archived_run_archive',
        ),
        'enabled' => 0,
        'restrictedusers' => 1,
        'shortname' => 'lifecycletrigger_archived',
        'downloadfiles' => 0,
        'uploadfiles' => 0
    ),
);

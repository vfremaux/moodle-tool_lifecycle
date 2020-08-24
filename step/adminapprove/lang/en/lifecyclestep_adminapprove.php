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
 * Lang Strings for Admin Approve Step
 *
 * @package lifecyclestep_adminapprove
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Admin Approve Step';
$string['emailsubject'] = 'Lifecycle: There are new courses waiting for confirmation.';
$string['emailcontent'] = 'There are {$a->amount} new courses waiting for confirmation. Please visit {$a->url}.';
$string['emailcontenthtml'] = 'There are {$a->amount} new courses waiting for confirmation. Please visit <a href="{$a->url}">this link</a>.';
$string['courseid'] = 'Course id';
$string['workflow'] = 'Workflow';
$string['proceedselected'] = 'Proceed selected';
$string['rollbackselected'] = 'Rollback selected';
$string['tools'] = 'Tools';
$string['courses_waiting'] = 'These courses are currently waiting for approval in the "{$a->step}" Step in the "{$a->workflow}" Workflow.';
$string['no_courses_waiting'] = 'There are currently no courses waiting for approval in the "{$a->step}" Step in the "{$a->workflow}" Workflow.';
$string['proceed'] = 'Proceed';
$string['rollback'] = 'Rollback';
$string['amount_courses'] = 'Remaining waiting courses';
$string['only_number'] = 'Only numeric characters allowed!';
$string['nothingtodisplay'] = 'There are no courses waiting for approval matching your current filters.';
$string['manage-adminapprove'] = 'Manage Admin Approve Steps';
$string['nostepstodisplay'] = 'There are currently no courses waiting for interaction in any Admin Approve step.';
$string['bulkactions'] = 'Bulk actions';
$string['proceedall'] = 'Proceed all';
$string['rollbackall'] = 'Rollback all';
$string['statusmessage'] = 'Status message';
$string['statusmessage_help'] = 'Status message, which is displayed to a teacher, if a process of a course is at this admin approve step.';
$string['privacy:metadata'] = 'The Admin Approve Step plugin does not store any personal data.';

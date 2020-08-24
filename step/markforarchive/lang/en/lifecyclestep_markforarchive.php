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
 * @package lifecyclestep_markforarchive
 * @copyright  2020 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Mark for archive Step';

$string['triggerid'] = 'Trigger';
$string['needstriggerid'] = 'An "archive complete" trigger instance must exist in the workflow.';
$string['privacy:metadata'] = 'The Mark for archive Step plugin does not store any personal data.';

$string['emailsubject'] = '{$a}: Some courses have been scheduled for archiving';

$string['emailcontent'] = '
Some courses have been scheduled for archiving

Number of courses : {$a->n}

Course list :
{$a->courses}
';

$string['emailcontenthtml'] = '
<h2>Some courses have been scheduled for archiving</h2>
<p>Number of courses : {$a->n}</p>
<p>Course list :</p>
<pre>
{$a->courses}
</pre>
';
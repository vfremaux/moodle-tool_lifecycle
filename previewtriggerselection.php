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
 * Displays the settings associated with one single workflow and handles action for it.
 *
 * @package tool_lifecycle
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/adminlib.php');
require_once($CFG->dirroot.'/admin/tool/lifecycle/classes/processor.php');
require_once($CFG->dirroot.'/admin/tool/lifecycle/classes/local/manager/lib_manager.php');

use tool_lifecycle\processor;
use tool_lifecycle\local\manager\lib_manager;
use tool_lifecycle\local\response\trigger_response;

$PAGE->set_context(context_system::instance());
require_login(null, false);
require_capability('moodle/site:config', context_system::instance());

$workflowid = required_param('workflowid', PARAM_INT);

$PAGE->set_url(new moodle_url('/admin/tool/lifecycle/previewtriggerselection.php', ['workflowid' => $workflowid]));

$renderer = $PAGE->get_renderer('tool_lifecycle');

$workflow = tool_lifecycle\local\manager\workflow_manager::get_workflow($workflowid);

if (!$workflow) {
    throw new moodle_exception('workflownotfound', 'tool_lifecycle',
        new \moodle_url('/admin/tool/lifecycle/adminsettings.php'), $workflowid);
}

$processor = new processor();
$triggers = $DB->get_records('tool_lifecycle_trigger', ['workflowid' => $workflowid], 'sortindex');
$rawcourselist = $processor->get_course_recordset($triggers, []);
$excludes = [];
$allcourses = 0;
$retainedcourses = [];
foreach ($rawcourselist as $cid => $course) {
    $allcourses++;
    foreach ($triggers as $t) {
        $lib = lib_manager::get_automatic_trigger_lib($t->subpluginname);
        $response = $lib->check_course($course, $t->id);
        if ($response == trigger_response::next()) {
            $rawcourselist->next();
            continue 2;
        }
        if ($response == trigger_response::exclude()) {
            array_push($excludes, $course->id);
            $countexcluded++;
            if (!isset($t->countexcluded)) {
                $t->countexcluded = 0;
            } else {
                $t->countexcluded++;
            }
            $rawcourselist->next();
            continue 2;
        }
        if ($response == trigger_response::trigger()) {
            $retainedcourses[$cid] = $course;
            continue;
        }
    }
}

$table = new html_table();
$shortnamestr = get_string('shortname');
$namestr = get_string('fullname');
$idnumberstr = get_string('idnumber');
$categorystr = get_string('category');
$table->head = ['Id', $shortnamestr, $idnumberstr, $categorystr, $namestr];
$table->width = '100%';
$table->size = ['2%', '10%', '10%', '30%', '48%'];

foreach ($retainedcourses as $cid => $course) {
    if (!array_key_exists($cid, $excludes)) {
        $category = coursecat::get($course->category);
        $table->data[] = [$cid, $course->shortname, $course->idnumber, $category->name, $course->fullname];
    }
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('workflowpreview', 'tool_lifecycle', $workflowid), 1);

echo $renderer->print_preview_globals($allcourses, 0 + $countexcluded, $triggers);

echo $OUTPUT->heading(get_string('results', 'tool_lifecycle'), 3);

if (empty($table->data)) {
    echo $OUTPUT->notification(get_string('nocoursematch', 'tool_lifecycle'));
} else {
    echo html_writer::table($table);
}

echo '<center>';
$returnurl = new moodle_url('/admin/tool/lifecycle/workflowsettings.php', ['workflowid' => $workflowid]);
echo $OUTPUT->single_button($returnurl, get_string('backtoworkflow', 'tool_lifecycle'));
echo '</center>';

echo $OUTPUT->footer();
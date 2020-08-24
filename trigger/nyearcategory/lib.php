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
 * Trigger subplugin to include or exclude courses of a millesimed category based on idnumber naming.
 *
 * @package lifecycletrigger_nyearcategory
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_lifecycle\trigger;

use coursecat;
use tool_lifecycle\local\manager\settings_manager;
use tool_lifecycle\local\response\trigger_response;
use tool_lifecycle\settings_type;

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/../lib.php');
require_once(__DIR__ . '/../../lib.php');

/**
 * Class which implements the basic methods necessary for a cleanyp courses trigger subplugin
 * @package lifecycletrigger_categories
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class nyearcategory extends base_automatic {

    /**
     * Checks the course and returns a repsonse, which tells if the course should be further processed.
     * @param object $course Course to be processed.
     * @param int $triggerid Id of the trigger instance.
     * @return trigger_response
     */
    public function check_course($course, $triggerid) {
        // Every decision is already in the where statement.
        return trigger_response::trigger();
    }

    /**
     * Return sql sniplet for including (or excluding) the courses belonging to specific categories
     * and all their children.
     * @param int $triggerid Id of the trigger.
     * @return array A list containing the constructed sql fragment and an array of parameters.
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_course_recordset_where($triggerid) {
        global $DB, $CFG;

        $catpattern = settings_manager::get_settings($triggerid, settings_type::TRIGGER)['idnumberpattern'];
        $n = settings_manager::get_settings($triggerid, settings_type::TRIGGER)['n'];
        $exclude = settings_manager::get_settings($triggerid, settings_type::TRIGGER)['exclude'] && true;

        $idnumber = str_replace('%Y', $this->millesim($n), $catpattern);
        $nyearcategoryid = $DB->get_field('course_categories', 'id', ['idnumber' => $idnumber]);

        if (empty($nyearcategoryid)) {
            return (['false', []]);
        }

        // Use core_course_category for moodle 3.6 and higher.
        if ($CFG->version >= 2018120300) {
            $category = \core_course_category::get($nyearcategoryid);
        } else {
            require_once($CFG->libdir . '/coursecatlib.php');
            $category = \coursecat::get($nyearcategoryid);
        }

        $allcategories = [];
        array_push($allcategories , $nyearcategoryid);
        $children = $category->get_all_children_ids();
        $allcategories  = array_merge($allcategories , $children);

        list($insql, $inparams) = $DB->get_in_or_equal($allcategories, SQL_PARAMS_NAMED);

        $where = "{course}.category {$insql}";
        if ($exclude) {
            $where = "NOT " . $where;
        }



        return array($where, $inparams);
    }

    /**
     * The return value should be equivalent with the name of the subplugin folder.
     * @return string technical name of the subplugin
     */
    public function get_subpluginname() {
        return 'nyearcategory';
    }

    /**
     * Defines which settings each instance of the subplugin offers for the user to define.
     * @return instance_setting[] containing settings keys and PARAM_TYPES
     */
    public function instance_settings() {
        return array(
            new instance_setting('idnumberpattern', PARAM_TEXT),
            new instance_setting('n', PARAM_INT),
            new instance_setting('exclude', PARAM_BOOL),
        );
    }

    /**
     * This method can be overriden, to add form elements to the form_step_instance.
     * It is called in definition().
     * @param \MoodleQuickForm $mform
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function extend_add_instance_form_definition($mform) {
        global $DB;

        $config = get_config('lifecycletrigger_nyearcategory');

        if (empty($config->defaultidnumberpattern)) {
            $config->defaultidnumberpattern = "Y%Y";
            set_config('defaultidnumberpattern', "Y%Y", 'lifecycletrigger_nyearcategory');
        }

        $options = [
            0 => 'N ('.$this->millesim(0).'-'.$this->millesim(-1).')',
            1 => 'N-1 ('.$this->millesim(1).'-'.$this->millesim(0).')',
            2 => 'N-2 ('.$this->millesim(2).'-'.$this->millesim(1).')',
            3 => 'N-3 ('.$this->millesim(3).'-'.$this->millesim(2).')',
            4 => 'N-4 ('.$this->millesim(4).'-'.$this->millesim(3).')',
            5 => 'N-5 ('.$this->millesim(5).'-'.$this->millesim(4).')',
        ];
        $mform->addElement('select', 'n', get_string('n', 'lifecycletrigger_nyearcategory'), $options);

        $mform->addElement('text', 'idnumberpattern', get_string('idnumberpattern', 'lifecycletrigger_nyearcategory'), ' size="40" ');
        $mform->setType('idnumberpattern', PARAM_TEXT);
        $mform->setDefault('idnumberpattern', $config->defaultidnumberpattern);
        $mform->setAdvanced('idnumberpattern');

        $mform->addElement('advcheckbox', 'exclude', get_string('exclude', 'lifecycletrigger_categories'));
        $mform->setType('exclude', PARAM_BOOL);
    }

    /**
     * Calculates the (shifted) millesim depending on switch date and current year.
     */
    protected function millesim($backshift) {
        $config = get_config('lifecycletrigger_nyearcategory');

        if (empty($config->yeardateswitch)) {
            $config->yeardateswitch = "08.01"; // August the 1st.
            set_config('yeardateswitch', "Y%Y", 'lifecycletrigger_nyearcategory');
        }

        list($mon, $day) = explode('.', $config->yeardateswitch);

        $current = strftime("%m.%d", time());
        $currentyear = strftime("%Y", time());

        if ($current < "$mon.$day") {
            $millesim = $currentyear - 1 - $backshift;
        } else {
            $millesim = $currentyear - $backshift;
        }

        return $millesim;
    }
}

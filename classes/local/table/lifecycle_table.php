<?php

namespace tool_lifecycle\local\table;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');

class lifecycle_table extends \table_sql {

    /**
     * This function is not part of the public api.
     */
    function print_nothing_to_display() {
        global $OUTPUT;

        // Render button to allow user to reset table preferences.
        echo $this->render_reset_button();

        $this->print_initials_bar();

        echo $OUTPUT->notification(get_string('nothingtodisplay'));
    }

}
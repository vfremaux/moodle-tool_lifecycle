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
 * Subplugin for the archive complete.
 *
 * An archive complete triggs when the course has a positive archiving reponse
 * from a remote archiving endpoint.
 *
 * @package lifecycletrigger_archived
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_lifecycle\trigger;

use tool_lifecycle\local\manager\settings_manager;
use tool_lifecycle\local\response\trigger_response;
use tool_lifecycle\settings_type;
use StdClass;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/admin/tool/lifecycle/lib.php');
require_once($CFG->dirroot.'/admin/tool/lifecycle/trigger/lib.php');
require_once($CFG->dirroot.'/admin/tool/lifecycle/trigger/archived/compatlib.php');

/**
 * Class which implements the basic methods necessary for a trigger subplugin
 * @package lifecycletrigger_archived
 * @copyright  202 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class archived extends base_automatic {

    const STATUS_STALLED = 0;

    const STATUS_WAITING_FOR_PULL = 1;

    const STATUS_ARCHIVED = 2;

    const STATUS_ARCHIVE_FAILURE = 99;

    /**
     * Checks the course and returns a repsonse, which tells if the course should be further processed.
     * @param object $course Course to be processed.
     * @param int $triggerid Id of the trigger instance.
     * @return trigger_response
     */
    public function check_course($course, $triggerid) {
        global $DB;

        // Everything is already in the sql statement.
        $params = ['triggerid' => $triggerid, 'courseid' => $course->id];
        $status = $DB->get_record('lifecycletrigger_archived', $params);

        if (!$status) {
            return trigger_response::exclude();
        }

        if ($status->status == self::STATUS_ARCHIVED) {
            return trigger_response::trigger();
        }

        return trigger_response::exclude();
    }

    /**
     * The return value should be equivalent with the name of the subplugin folder.
     * @return string technical name of the subplugin
     */
    public function get_subpluginname() {
        return 'archived';
    }

    /**
     * Defines which settings each instance of the subplugin offers for the user to define.
     * @return instance_setting[] containing settings keys and PARAM_TYPES
     */
    public function instance_settings() {
        return array(
            new instance_setting('remotewwwroot', PARAM_TEXT)
        );
    }

    /**
     * At the delay since the start date of a course.
     * @param \MoodleQuickForm $mform
     * @throws \coding_exception
     */
    public function extend_add_instance_form_definition($mform) {
        $mform->addElement('text', 'remotewwwroot', get_string('remotewwwroot', 'lifecycletrigger_archived'));
        $mform->setType('remotewwwroot', PARAM_TEXT);
        $mform->addHelpButton('remotewwwroot', 'remotewwwroot', 'lifecycletrigger_archived');
    }

    /**
     * In archiver node.
     *
     * Part of the reverse implementation of archiving (pulled by archive moodle)
     * Archive all (or part) of archivable courses. This runs a process that :
     * - calls the source moodle to get the list of archivable courses
     * - for each course :
     * - invokes the importer plugin to get the course restored in a target category
     * - calls the source back to acknowledge the restore and update source status.
     *
     */
    public static function task_pull_and_archive_courses() {
        global $CFG, $DB;
        global $verbose;
        $verbose = true;

        $lifecycleconfig = get_config('lifecycletrigger_archived');

        if (is_dir($CFG->dirroot.'/blocks/import_course')) {
            include_once($CFG->dirroot.'/blocks/import_course/xlib.php');
            $pullcomponent = 'block_import_courses';
            $config = get_config('block_import_course');
            if ($verbose) {
                echo "Using import_course block for transport\n";
            }
        } else {
            if (is_dir($CFG->dirroot.'/blocks/publishflow')) {
                include_once($CFG->dirroot.'/blocks/publishflow/xlib.php');
                $pullcomponent = 'block_publishflow';
            }
            if ($verbose) {
                echo "Using publishflow block for transport\n";
            }
        }

        $archivables = self::get_archivables();

        $perruncounter = 0;

        if (!empty($archivables)) {
            foreach ($archivables as $arch) {
                // Trigger a pull.
                if ($verbose) {
                    echo "Archiving remote $arch->id ($arch->shortname) \n";
                }
                if ($pullcomponent == 'block_import_courses') {
                    try {
                        if (empty($lifecycleconfig->preservesourcecategory)) {
                            if (!$DB->record_exists('course_categories', ['id' => $config->targetcategory])) {
                                throw new \moodle_exception('Target category '.$config->targetcategory.' was not found.');
                            }
                            $targetcategoryid = $config->targetcategory;
                        } else {
                            $categorypath = $arch->sourcecategorypath;
                            $targetcategoryid = self::check_category_path($categorypath);
                        }
                        $courseid = block_import_course_import($config->teachingurl, $config->teachingtoken, $targetcategoryid, $arch->id, [], 1);
                    } catch (Exception $e) {
                        if ($verbose) {
                            echo "Archiving {$arch->id} Failed. \n";
                        }
                        if (function_exists('debug_trace')) {
                            debug_trace("Import of source course {$arch->id} backup has failed for some reason. Notify failed.");
                        }
                        self::notify_source($arch->id, $arch->triggerid, false);
                        continue;
                    }

                    if ($verbose) {
                        echo "Archiving success of origin {$arch->id} ";
                        if (function_exists('debug_trace')) {
                            debug_trace("Archiving complete. \n");
                        }
                    }
                    // Notifiy archiving success.
                    self::notify_source($arch->id, $arch->triggerid, true);
                }
                // Publishflow transport is not implemented yet.
                $perruncounter++;

                if ($perruncounter > $lifecycleconfig->maxcoursepullspercron) {
                    return;
                }
            }
        } else {
            if ($verbose) {
                echo "Nothing found to archive \n";
            }
        }
    }

    /**
     * Scans a category path and create missing nodes in the tree if needed.
     * @param string $categorypath
     * @return int leaf category id.
     */
    public static function check_category_path($categorypath) {
        global $DB;

        $categories = explode('/', $categorypath);

        // Always starts at tree root.
        $parentcategoryid = 0;

        while ($catname = array_shift($categories)) {

            $catdata = new Stdclass();
            $catdata->parent = $parentcategoryid;
            $catdata->name = trim($catname);

            $updated = false;
            // idnumber is the only external unique identification.
            $params = array('parent' => $parentcategoryid, 'name' => $catdata->name);
            if ($oldcat = $DB->get_record('course_categories', $params)) {
                $cat = $oldcat;
                debug_trace("Category {$oldcat->id} exists ");
            } else {
                $cat = lifecycletrigger_archived_create_category($catdata);
                if ($parentcategoryid) {
                    $parentcat = $DB->get_field('course_categories', 'name', array('id' => $parentcategoryid));
                    if (function_exists('debug_trace')) {
                        debug_trace('Category '.$catdata->name.' added to parent cat '.$parentcat);
                    }
                } else {
                    if (function_exists('debug_trace')) {
                        debug_trace('Category '.$catdata->name.' added to root cat ');
                    }
                }
            }

            // For next turn.
            $parentcategoryid = $cat->id;
        }

        // Returns the lowest leaf cat id.
        return $cat->id;
    }

    /**
     * Get all archivable courses from a remote moodle (works on archive node only).
     */
    public static function get_archivables() {
        global $CFG, $verbose;

        $config = get_config('lifecycletrigger_archived');

        $url = $config->archivesourcewwwroot;
        $url .= '/webservice/rest/server.php';
        $url .= '?wsfunction=lifecycletrigger_archived_get_archivable_courses';
        $url .= '&wstoken='.$config->archivesourcetoken;
        $url .= '&remotewwwroot='.urlencode($CFG->wwwroot);
        $url .= '&triggerid=0';
        $url .= '&moodlewsrestformat=json';

        $res = curl_init($url);
        self::set_proxy($res, $url);
        curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($res, CURLOPT_POST, false);

        if ($verbose) {
            echo "Firing CURL : $url \n";
        }
        $result = curl_exec($res);
        if ($verbose) {
            if ($result) {
                echo "$result \n";
            }
        }

        // Get result content and status.
        if ($result) {
            $archivables = json_decode($result);

            if (!empty($archivables->exception)) {
                print_error($archivables->message);
            }

            if ($archivables) {
                return $archivables;
            }
        } else {
            if ($verbose) {
                echo "CURL response failed or empty";
            }
        }

        return false;
    }

    /**
     * Notify source that course restore is done and ask for postactions.
     */
    public static function notify_source($courseid, $triggerid, $result = true) {
        global $verbose;

        $config = get_config('lifecycletrigger_archived');

        $url = $config->archivesourcewwwroot;
        $url .= '/webservice/rest/server.php';
        $url .= '?wsfunction=lifecycletrigger_archived_update_course_status';
        $url .= '&wstoken='.$config->archivesourcetoken;
        $url .= '&moodlewsrestformat=json';
        $url .= '&courseidfield=id';
        $url .= '&courseid='.$courseid;
        $url .= '&triggerid='.$triggerid;
        $url .= '&result='.$result;

        $res = curl_init($url);
        self::set_proxy($res, $url);
        curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($res, CURLOPT_POST, false);

        if ($verbose) {
            echo "Firing Notify CURL : $url \n";
        }

        $result = curl_exec($res);

        // Get result content and status.
        if (!$result) {
            debug_trace("Failed notifying. Empty remote response.");
            if ($verbose) {
                echo "Failed notifying. Empty remote response\n";
                return false;
            }
        } else {
            $result = json_decode($result);
            if (!empty($result->exception)) {
                echo "CURL Remote service failed because of\n";
                echo $result->message."\n";
            }
            return false;
        }

        return true;
    }

    protected static function set_proxy(&$res, $url) {
        global $CFG;

        // Check for proxy.
        if (!empty($CFG->proxyhost) and !is_proxybypass($url)) {
            // SOCKS supported in PHP5 only
            if (!empty($CFG->proxytype) and ($CFG->proxytype == 'SOCKS5')) {
                if (defined('CURLPROXY_SOCKS5')) {
                    curl_setopt($res, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
                } else {
                    curl_close($res);
                    print_error('socksnotsupported', 'mnet');
                }
            }

            curl_setopt($res, CURLOPT_HTTPPROXYTUNNEL, false);

            if (empty($CFG->proxyport)) {
                curl_setopt($res, CURLOPT_PROXY, $CFG->proxyhost);
            } else {
                curl_setopt($res, CURLOPT_PROXY, $CFG->proxyhost.':'.$CFG->proxyport);
            }

            if (!empty($CFG->proxyuser) and !empty($CFG->proxypassword)) {
                curl_setopt($res, CURLOPT_PROXYUSERPWD, $CFG->proxyuser.':'.$CFG->proxypassword);
                if (defined('CURLOPT_PROXYAUTH')) {
                    // any proxy authentication if PHP 5.1
                    curl_setopt($res, CURLOPT_PROXYAUTH, CURLAUTH_BASIC | CURLAUTH_NTLM);
                }
            }
        }
    }
}

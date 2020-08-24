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

$string['pluginname'] = 'Déclencheur : cours archivé';

$string['description'] = 'Excute une action quand le cours a été marqué comme archivé';
$string['description_help'] = 'L\'action sera exécutée quand la plate-forme d\'archive distante a signalé que le cours était correctement récupéré pour archive.';

$string['remotewwwroot'] = 'Url d\'archivage';
$string['remotewwwroot_help'] = 'Url de la plate-forme moodle distante d\'archivage (wwwroot)';
$string['task_pull_and_archive'] = 'Examine la liste distante des cours archivables et les transfère sur la plate-forme courante.'; // @DYNA.
$string['configmaxcoursepullspercron'] = 'Nombre maximum de transferts par cron'; // @DYNA.
$string['configmaxcoursepullspercron_desc'] = 'Définit le nombre maximum de transferts (restaurations) par instance de cron.
Laissez ce nombre suffisamment faible pour que les autres tâches puissent être exécutées régulièrement.';

$string['configarchivesourcewwwroot'] = 'Source des archives';
$string['configarchivesourcewwwroot_desc'] = 'Une plate-forme moodle distante à archiver';
$string['configarchivesourcetoken'] = 'Token de la source';
$string['configarchivesourcetoken_desc'] = 'Le token de web services de la plate-forme source';

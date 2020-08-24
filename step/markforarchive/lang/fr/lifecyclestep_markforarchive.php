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
 * @copyright  2020 Valery Fremaux (valery.femaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Action : Marquer pour archive';

$string['triggerid'] = 'Déclencheur';
$string['needstriggerid'] = 'Le workflow doit contenir au moins un déclencheur de type "Cours Archivé" pour associer cette action.';

$string['emailsubject'] = '{$a}: Des cours ont été marqués pour archivage';

$string['emailcontent'] = '
Des cours ont été marqués pour archivage

Nombre de cours : {$a->n}

Liste :
{$a->courses}
';

$string['emailcontenthtml'] = '
<h2>Des cours ont été marqués pour archivage</h2>
<p>Nombre de cours : {$a->n}</p>
<p>Liste :</p>
<pre>
{$a->courses}
</pre>
';
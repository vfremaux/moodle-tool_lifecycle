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

$string['pluginname'] = 'Action : Approbation par l\'administrateur';
$string['emailsubject'] = '{$a} : Des cours attendent votre confirmation pour une action du cycle de vie.';
$string['emailcontent'] = '{$a->amount} nouveaux cours attendent votre confirmation. Allez à l\'adresse  {$a->url}.';
$string['emailcontenthtml'] = '{$a->amount} nouveaux cours attendent votre confirmation. <a href="{$a->url}">voir les cours</a>.';
$string['courseid'] = 'ID de cours';
$string['workflow'] = 'Workflow';
$string['proceedselected'] = 'Proceed selected';
$string['rollbackselected'] = 'Rollback selected';
$string['tools'] = 'Outils';
$string['courses_waiting'] = 'Ces cours sont actuellement en attente de traitement à l\'étape "{$a->step}" du workflow "{$a->workflow}".';
$string['no_courses_waiting'] = 'Il n\'y a aucun cours en attente dans l\'étape "{$a->step}" du workflow "{$a->workflow}".';
$string['proceed'] = 'Continuer le traitement';
$string['rollback'] = 'Annuler le traitement';
$string['amount_courses'] = 'Cours en attente';
$string['only_number'] = 'Saisie numérique uniquement !';
$string['nothingtodisplay'] = 'Il n\'y a aucun cours en attente de traitement compte tenu de la configuration actuelle des filtres.';
$string['manage-adminapprove'] = 'Actions d\'approbation administrateur';
$string['nostepstodisplay'] = 'Il n\'y a aucun cours en attente d\'approbation administrateur.';
$string['bulkactions'] = 'Actions de masse';
$string['proceedall'] = 'Traiter tout';
$string['rollbackall'] = 'Annuler tout';
$string['statusmessage'] = 'Message d\'état';
$string['statusmessage_help'] = 'Message affiché pour l\'enseigant, si le traitement de ce cours est en attente d\'approbation administrateur.';
$string['privacy:metadata'] = 'Le plugin Action approbation administrateur ne détient aucune donnée personnelle.';

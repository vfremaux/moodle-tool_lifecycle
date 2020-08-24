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
 * Lang strings for Nth year category trigger
 *
 * @package lifecycletrigger_nyearcategory
 * @copyright  2020 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Déclencheur: Inclusion/exclusion catégorie de session annuelle';

$string['nyearcategory'] = 'Ce plugin permet de déclencher (ou exclure) sur la base de l\'appartenance à une catégorie identifiée
comme étant une catégorie de session annuelle, reconnue par son numéro d\'identification.';
$string['configdefaultidnumberpattern'] = 'Motif par défaut du numéro d\'identification';
$string['configdefaultidnumberpattern_desc'] = 'Motif du numéro d\'identification par défaut pour les nouvelles instances. Marquer le millesime par %Y.';
$string['configyeardateswitch'] = 'Date à laquelle le millésime change';
$string['configyeardateswitch_desc'] = 'Utilisez la syntaxe MM.JJ';
$string['n'] = 'L\'index relatif du millésime. <b>Attention</b>, la date de basculement d\'année académique influe sur la sélection.';
$string['exclude'] = 'Inverse le test sur la catégorie sélectionnée. Si coché, les catégories choisies seront exclues du traitement.';

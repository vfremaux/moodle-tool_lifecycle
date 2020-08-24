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
 * Lang strings for email step
 *
 * @package lifecyclestep_email
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Action : Envoi de courriel';

$string['email_responsetimeout'] = 'Temps accordé pour la réponse';
$string['email_subject'] = 'Sujet du courriel';
$emailplaceholdersnohtml = '<p>' . 'Vous pouvez utiliser les balises d\'insertion suivantes :'
    . '<br>' . 'Prénom du destinataire : ##firstname##'
    . '<br>' . 'Nom du destinataire : ##lastname##'
    . '<br>' . 'Lien vers le formulaire de réponse : ##link##'
    . '<br>' . 'Cours concernés: ##courses##'
    . '</p>';
$string['email_subject_help'] = 'Définissez le modèle de sujet du courriel .' . $emailplaceholdersnohtml;
$string['email_content'] = 'Contenu du courriel (texte)';
$string['email_content_help'] = 'Définissez le modèle de contenu du courriel (text, utilsez le modèle HTML pour un mail en HTML) ' . $emailplaceholdersnohtml;
$emailplaceholdershtml = '<p>' . 'Vous pouvez utiliser les balises d\'insertion suivantes :'
    . '<br>' . 'Prénom du destinataire : ##firstname##'
    . '<br>' . 'Nom du destinataire : ##lastname##'
    . '<br>' . 'Lien vers la page de réponse : ##link-html##'
    . '<br>' . 'Cours concernés : ##courses-html##'
    . '</p>';
$string['email_content_html'] = 'Contenu HTML du courriel';
$string['email_content_html_help'] = 'Définissez le modèle de contenu du courriel (HTML, utilisé à la place du modèle texte si non vide)' . $emailplaceholdershtml;

$string['email:preventdeletion'] = 'Empêcher la suppression';

$string['keep_course'] = 'Garder le cours';
$string['status_message_requiresattention'] = 'La suppression du cours est programmée';
$string['action_prevented_deletion'] = '{$a} a empêché la suppression';

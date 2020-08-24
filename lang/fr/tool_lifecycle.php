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
 * Life cycle langauge strings.
 *
 * @package tool_lifecycle
 * @copyright  2017 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


$string['pluginname'] = 'Cycle de vie';
$string['plugintitle'] = 'Cycle de vie des cours';

$string['lifecycle:managecourses'] = 'Gère le cycle de vie des cours';
$string['managecourses_link'] = 'Gérer les cours';

$string['general_config_header'] = "Configuration générale et sous-plugins";
$string['config_delay_duration'] = 'Délai d\'action de cours par défaut';
$string['config_delay_duration_desc'] = 'Ce réglage détermine le délai par défaut avant lequel l\'opération suivante
peut être jouée un cours, lorsqu\'une action se termine.';
$string['config_backup_path'] = 'Chemin pour les fichiers de sauvegarde';
$string['config_backup_path_desc'] = 'Détermine le chemin physique où les fichiers de sauvegarde seront stockés pour l\'action de sauvegarde.
Le chemin doit être donné comme un chemin absolu sur le serveur.';
$string['active_processes_list_header'] = 'Actions actives';
$string['adminsettings_heading'] = 'Configuration des workflows';
$string['active_manual_workflows_heading'] = 'Workflows actifs en déclenchement manuel';
$string['active_automatic_workflows_heading'] = 'Workflows automatiques';
$string['workflow_definition_heading'] = 'Définitions du Workflow';
$string['adminsettings_edit_workflow_definition_heading'] = 'Définition du Workflow';
$string['adminsettings_workflow_definition_steps_heading'] = 'Actions du Workflow';
$string['adminsettings_edit_trigger_instance_heading'] = 'Déclencheur du workflow \'{$a}\'';
$string['adminsettings_edit_step_instance_heading'] = 'Action du workflow \'{$a}\'';
$string['add_new_step_instance'] = 'Ajouter une nouvelle action...';
$string['add_new_trigger_instance'] = 'Ajouter un nouveau déclencheur...';
$string['step_settings_header'] = 'Paramètres spécifique d\'action';
$string['trigger_settings_header'] = 'Paramètres spécifiques de déclencheur';
$string['general_settings_header'] = 'Paramètres généraux';
$string['followedby_none'] = 'Aucun';
$string['invalid_workflow'] = 'Configuration invalide';
$string['invalid_workflow_details'] = 'Aller à la vue de détail, pour créer un déclencheur sur ce workflow';
$string['active_workflow_not_changeable'] = 'Ce workflow est actif. Il n\'est plus possible d\'en changer les étapes.';
$string['active_workflow_not_removeable'] = 'Ce workflow est actif. Vous ne pouvez pas le supprimer.';
$string['workflow_not_removeable'] = 'Il n\'est pas possible de supprimer ce workflow. Peut-être la raison est qu\'il a encore des processus en cours.';
$string['invalid_workflow_cannot_be_activated'] = 'La définition du workflow est invalide, il ne peut être lancé.';
$string['trigger_does_not_exist'] = 'Le déclencheur n\'a pas pu être trouvé.';
$string['cannot_trigger_workflow_manually'] = 'Le workflow n\'a pas pu être déclenché manuellement.';
$string['error_wrong_trigger_selected'] = 'Vous essayez de déclencher un déclencheur automatique.';
$string['workflowpreview'] = 'Test de sélection de cours du workflow {$a}';
$string['nocoursematch'] = 'Aucun cours correspondant au filtrage en cours.';
$string['backtoworkflow'] = 'Revenir au workflow';
$string['previewworkflowselection'] = 'Prévisualiser la sélection de cours';
$string['pertrigger'] = 'Par déclencheur';
$string['forworkflow'] = 'Pour le workflow';
$string['totalcourses'] = 'Total des cours examinés';
$string['totalexcluded'] = 'Total des cours exclus';
$string['results'] = 'Cours résultants';
$string['sitecourseexclude'] = 'Exclusion du cours "site"';

$string['lifecycle_task'] = 'Exécuter les tâches de cycle de vie';
$string['lifecycle_cleanup_task'] = 'Nettoyage des entrées obsolètes du cycle de vie';

$string['trigger_subpluginname'] = 'Nom du sous-plugin';
$string['trigger_subpluginname_help'] = 'Le titre visible du déclencheur (visible par l\'administrateur seulement).';
$string['trigger_instancename'] = 'Nom d\'instance';
$string['trigger_instancename_help'] = 'Titre du déclencheur (visible par l\'administrateur seulement).';
$string['trigger_enabled'] = 'Actif';
$string['trigger_sortindex'] = 'Haut/Bas';
$string['trigger_workflow'] = 'Workflow';

$string['workflow'] = 'Workflow';
$string['add_workflow'] = 'Ajouter un workflow';
$string['upload_workflow'] = 'Importer un workflow';
$string['workflow_title'] = 'Titre';
$string['workflow_title_help'] = 'Titre du workflow (visible par l\'administrateur seulement).';
$string['workflow_displaytitle'] = 'Titre visible du workflow';
$string['workflow_displaytitle_help'] = 'Ce titre est affiché aux enseignants dans l\administration du cours.';
$string['workflow_rollbackdelay'] = 'Délai de récupération (en cas d\'annulation)';
$string['workflow_rollbackdelay_help'] = 'Si une opération de cours a été annulée,
il s\'agit du temps avant lequel ce cours peut être à nouveau traité par une action.';
$string['workflow_finishdelay'] = 'Délai sur la fin de traitement';
$string['workflow_finishdelay_help'] = 'Pour un cours dont le traitement vient de se terminer,
il s\'agit du temps avant lequel ce cours peut être à nouveau traité par cette instance de workflow.';
$string['workflow_delayforallworkflows'] = 'Délai pour tous les workflows ?';
$string['workflow_delayforallworkflows_help'] = 'Si coché, les durées ci-dessus s\'appliqueront à tous les workflows.';
$string['workflow_active'] = 'Actif';
$string['workflow_processes'] = 'Actions actives';
$string['workflow_timeactive'] = 'Actif depuis';
$string['workflow_sortindex'] = 'Haut/Bas';
$string['workflow_tools'] = 'Actions';
$string['viewsteps'] = 'Voir les actions';
$string['editworkflow'] = 'Modifier les réglages généraux';
$string['backupworkflow'] = 'Sauvegarder le workflow';
$string['duplicateworkflow'] = 'Copier le workflow';
$string['deleteworkflow'] = 'Supprimer le workflow';
$string['deleteworkflow_confirm'] = 'Le workflow va être supprimé. Ceci ne pourra être récupéré. Voulez-vous continuer ?';
$string['activateworkflow'] = 'Activer';
$string['disableworkflow'] = 'Désactiver le workflow (les actions en cours se poursuivent)';
$string['disableworkflow_confirm'] = 'Ce workflow va être désactivé. Voulez-vous continuer ?';
$string['abortdisableworkflow'] = 'Arrêter le workflow (les tâches en cours seront arrêtées, et l\'état final n\'est pas garanti !)';
$string['abortdisableworkflow_confirm'] = 'Le workflow va être arrêté et toutes les actions vont être avortées ou supprimées. Voulez-vous continuer ?';
$string['abortprocesses'] = 'Stopper toutes les actions en cours (Etat final non garanti !)';
$string['abortprocesses_confirm'] = 'Toutes les actions en cours vont être arrêtées. Voulez-vous continuer ?';
$string['workflow_duplicate_title'] = '{$a} (Copie)';

// Deactivated workflows.
$string['deactivated_workflows_list'] = 'Lister les workflows désactivés';
$string['deactivated_workflows_list_header'] = 'Workflows désactivés';
$string['workflow_timedeactive'] = 'Désactivés depuis ';
$string['active_workflows_list'] = 'Lister les workflows actifs et les définitions';

$string['step_type'] = 'Type';
$string['step_subpluginname'] = 'Nom du sous-plugin';
$string['step_subpluginname_help'] = 'Le titre visible de l\'action (visible par l\'administrateur seulement).';
$string['step_instancename'] = 'Nom d\'instance';
$string['step_instancename_help'] = 'Titre de l\'instance d\'action  (visible par l\'administrateur seulement).';
$string['step_sortindex'] = 'Haut/Bas';
$string['step_edit'] = 'Modifier';
$string['step_show'] = 'Montrer';
$string['step_delete'] = 'Supprimer';

$string['trigger'] = 'Déclencheur';
$string['step'] = 'Action';

$string['workflow_trigger'] = 'Déclencheur du workflow';

$string['lifecycletrigger'] = 'Déclencheur';
$string['lifecyclestep'] = 'Action';

$string['subplugintype_lifecycletrigger'] = 'Déclencheur du workflow';
$string['subplugintype_lifecycletrigger_plural'] = 'Déclencheurs du workflow';
$string['subplugintype_lifecyclestep'] = 'Action du workflow';
$string['subplugintype_lifecyclestep_plural'] = 'Actions du workflow';

$string['nointeractioninterface'] = 'Pas d\'interface interactive disponible !';
$string['tools'] = 'Outils';
$string['status'] = 'Etat';
$string['date'] = 'Date';

$string['nostepfound'] = 'Cet ID d\'action n\'existe pas !';
$string['noprocessfound'] = 'Cet ID de tâche n\'existe pas !';

$string['nocoursestodisplay'] = 'Aucun cours n\'est concerné actuellement.';

$string['course_backups_list_header'] = 'Sauvegardes de cours';
$string['backupcreated'] = 'Créé le ';
$string['restore'] = 'Restauration';
$string['download'] = 'Téléchargement';

$string['workflownotfound'] = 'Cet ID ({$a}) de workflow n\existe pas !';

// View.php.
$string['tablecoursesrequiringattention'] = 'Cours concernés';
$string['tablecoursesremaining'] = 'Cours restants';
$string['tablecourseslog'] = 'Actions antérieures';
$string['viewheading'] = 'Gérer les cours';
$string['interaction_success'] = 'Action enregistrée.';
$string['manual_trigger_success'] = 'Le workflow a démarré.';
$string['manual_trigger_process_existed'] = 'Ce cours est déjà engagé dans un workflow.';

$string['coursename'] = 'Cours ';
$string['lastaction'] = 'Dernière action ';
$string['anonymous_user'] = 'Utilisateur anonyme';

$string['workflow_started'] = 'Workflow démarré.';
$string['workflow_is_running'] = 'Le workflow est en cours.';

// Backup & Restore.
$string['restore_workflow_not_found'] = 'Format du fichier de sauvegarde invalide. Les données de workflow n\'ont pas pu être lues.';
$string['restore_subplugins_invalid'] = 'Format du fichier de sauvegarde invalide. La structure de certains éléments de sous-plugins n\'est pas respectée.';
$string['restore_step_does_not_exist'] = 'L\'action {$a} n\'est pas installée, mais est présente dans le fichier de sauvegarde. Installez le sous-plugin et réessayez l\'import.';
$string['restore_trigger_does_not_exist'] = 'Le déclencheur {$a} n\'est pas installé, mais est présent dans le fichier de sauvegarde. Installez le sous-plugin et réessayez l\'import.';

// Events.
$string['process_triggered_event'] = 'Une action a été déclenchée';
$string['process_proceeded_event'] = 'Une action à terminé';
$string['process_rollback_event'] = 'Une action a été annulée';

// Privacy API.
$string['privacy:metadata:tool_lifecycle_action_log'] = 'Un journal des actions faites par les gestionnaires de cours.';
$string['privacy:metadata:tool_lifecycle_action_log:processid'] = 'ID de l\'action.';
$string['privacy:metadata:tool_lifecycle_action_log:workflowid'] = 'ID du workflow de l\'action.';
$string['privacy:metadata:tool_lifecycle_action_log:courseid'] = 'ID diu cours concerné par l\'action';
$string['privacy:metadata:tool_lifecycle_action_log:stepindex'] = 'Index de l\'action dans le workflow.';
$string['privacy:metadata:tool_lifecycle_action_log:time'] = 'Heure de déclenchement de l\'action.';
$string['privacy:metadata:tool_lifecycle_action_log:userid'] = 'ID de l\'utilisateur responsable de l\'action.';
$string['privacy:metadata:tool_lifecycle_action_log:action'] = 'Identifiant de l\'action.';

// Delays.
$string['delayed_courses_header'] = 'Cours en attente (délai)';
$string['delete_delay'] = 'Délai de suppression';
$string['globally_until_date'] = 'Globalement jusqu\'au {$a}';
$string['name_until_date'] = '"{$a->name}" jusqu\'au {$a->date}';
$string['delayed_globally_and_seperately'] = 'En attente globalement et séparément pour {$a} workflows';
$string['delayed_globally_and_seperately_for_one'] = 'En attente globalement et séparément pour 1 workflow';
$string['delayed_globally'] = 'En attente (globalement) jusqu\'au {$a}';
$string['delayed_for_workflow_until'] = '"{$a->name}" en attente jusqu\'au {$a->date}';
$string['delayed_for_workflows'] = 'En attente pour {$a} workflows';
$string['delays'] = 'Délais';
$string['apply'] = 'Appliquer';
$string['show_delays'] = 'Type de vue';
$string['all_delays'] = 'Tous les délais';
$string['globally'] = 'Délais globaux';
$string['delays_for_workflow'] = 'Délais pour"{$a}"';
$string['delete_all_delays'] = 'Supprimer tous les délais';

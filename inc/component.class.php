<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2012 by the Plugin Monitoring for GLPI Development Team.

   https://forge.indepnet.net/projects/monitoring/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of Plugin Monitoring project.

   Plugin Monitoring for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   Plugin Monitoring for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringComponent extends CommonDBTM {
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['component'][0];
   }
   
   
   /*
    * Add some services Component at install
    * 
    */
   function initComponents() {
      
      
      
   }



   function canCreate() {
      return haveRight('computer', 'w');
   }


   
   function canView() {
      return haveRight('computer', 'r');
   }


   
   function canCancel() {
      return haveRight('computer', 'w');
   }


   
   function canUndo() {
      return haveRight('computer', 'w');
   }


   
   function canValidate() {
      return true;
   }

   

   function getSearchOptions() {
      global $LANG;

      $tab = array();
    
      $tab['common'] = $LANG['plugin_monitoring']['component'][0];

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = $LANG['common'][16];
		$tab[1]['datatype'] = 'itemlink';

      $tab[2]['table']         = $this->getTable();
      $tab[2]['field']         = 'id';
      $tab[2]['name']          = $LANG['common'][2];
      $tab[2]['massiveaction'] = false; // implicit field is id
     
      return $tab;
   }

   

   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      
      return $ong;
   }

  
   
   /**
   * Display form for service configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;


      $pMonitoringCommand = new PluginMonitoringCommand();
      

      if ($items_id == '0') {
         $this->getEmpty();
         $this->fields['active_checks_enabled']  = 1;
         $this->fields['passive_checks_enabled'] = 1;
      } else {
         $this->getFromDB($items_id);
      }

      $this->showTabs($options);
      $this->showFormHeader($options);
      
      echo "<tr>";
      echo "<td>";
      echo $LANG['common'][16]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='hidden' name='is_template' value='1' />";
      $objectName = autoName($this->fields["name"], "name", 1,
                             $this->getType());
      autocompletionTextField($this, 'name', array('value' => $objectName));      
      echo "</td>";
      // * checks
      echo "<td>".$LANG['plugin_monitoring']['check'][0]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::show("PluginMonitoringCheck", 
                        array('name'=>'plugin_monitoring_checks_id',
                              'value'=>$this->fields['plugin_monitoring_checks_id']));
      echo "</td>";
      echo "</tr>";
      
      // * Link
      echo "<tr>";
      echo "<td>";
//      echo "Type of template&nbsp;:";
      echo "</td>";
      echo "<td>";
//      $a_types = array();
//      $a_types[''] = DROPDOWN_EMPTY_VALUE;
//      $a_types['partition'] = "Partition";
//      $a_types['processor'] = "Processor";
//      Dropdown::showFromArray("link", $a_types, array('value'=>$this->fields['link']));
      echo "</td>";
      // * active check
      echo "<td>";
      echo $LANG['plugin_monitoring']['host'][5]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo Dropdown::showYesNo("active_checks_enabled", $this->fields['active_checks_enabled']);
      echo "</td>";
      echo "</tr>";
      
      // * command
      echo "<tr>";
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][5]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $pMonitoringCommand->getFromDB($this->fields['plugin_monitoring_commands_id']);
      Dropdown::show("PluginMonitoringCommand", array(
                             'name' =>'plugin_monitoring_commands_id',
                              'value'=>$this->fields['plugin_monitoring_commands_id']
                              ));
      echo "</td>";
      // * passive check
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][7]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo Dropdown::showYesNo("passive_checks_enabled", $this->fields['passive_checks_enabled']);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][12]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $a_templates = array();
      $a_templates[''] = DROPDOWN_EMPTY_VALUE;
      if ($handle = opendir(GLPI_PLUGIN_DOC_DIR."/monitoring/templates/")) {
          while (false !== ($entry = readdir($handle))) {
              if ($entry != "." && $entry != "..") {
                 if (strstr($entry, "_graph.json")) {
                    $entry = str_replace("_graph.json", "", $entry);
                    $a_templates[$entry] = $entry;
                 }
              }
          }
          closedir($handle);
      }
      Dropdown::showFromArray("graph_template", 
                              $a_templates, 
                              array('value'=>$this->fields['graph_template']));
      echo "</td>";
      // * calendar
      echo "<td>".$LANG['plugin_monitoring']['host'][9]."&nbsp;:</td>";
      echo "<td>";
      dropdown::show("Calendar", array('name'=>'calendars_id',
                                 'value'=>$this->fields['calendars_id']));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_monitoring']['service'][8]."</th>";
      echo "</tr>";
      
      echo "<tr>";
      // * remotesystem
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][9]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $input = array();
      $input[''] = '------';
      $input['byssh'] = 'byssh';
      $input['nrpe'] = 'nrpe';
      $input['nsca'] = 'nsca';
      Dropdown::showFromArray("remotesystem", 
                              $input, 
                              array('value'=>$this->fields['remotesystem']));
      echo "</td>";      
      // * is_argument
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][10]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("is_arguments", $this->fields['is_arguments']);
      echo "</td>"; 
      echo "</tr>";
      
      echo "<tr>";
      // alias command
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][11]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='alias_command' value='".$this->fields['alias_command']."' />";
      echo "</td>"; 
      echo "<td colspan='2'></td>";
      echo "</tr>";
      
      
      // * Manage arguments
      $array = array();
      $a_displayarg = array();
      if (isset($pMonitoringCommand->fields['command_line'])) {
         preg_match_all("/\\$(ARG\d+)\\$/", $pMonitoringCommand->fields['command_line'], $array);
         $a_arguments = importArrayFromDB($this->fields['arguments']);
         foreach ($array[0] as $arg) {
            if (strstr($arg, "ARG")) {
               $arg = str_replace('$', '', $arg);
               if (!isset($a_arguments[$arg])) {
                  $a_arguments[$arg] = '';
               }
               $a_displayarg[$arg] = $a_arguments[$arg];
               
            }
         }
      }
      if (count($a_displayarg) > 0) {
         $a_argtext = importArrayFromDB($pMonitoringCommand->fields['arguments']);
         echo "<tr>";
         echo "<th colspan='4'>".$LANG['plugin_monitoring']['service'][4]."&nbsp;</th>";
         echo "</tr>";
          
         foreach ($a_displayarg as $key=>$value) {
         echo "<tr>";
         echo "<td>";
            if (isset($a_argtext[$key])) {
               echo nl2br($a_argtext[$key])."&nbsp;:";
            } else {
               echo $LANG['plugin_monitoring']['service'][14]."&nbsp;:";
            }
            echo "</td>";
            echo "<td>";
            echo "<input type='text' name='arg[".$key."]' value='".$value."'/><br/>";
            echo "</td>";
            echo "<td colspan='2'></td>";
            echo "</tr>";
         }
      }
      
      $this->showFormButtons($options);
      return true;
   }
}

?>
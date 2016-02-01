<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2015 by the Plugin Monitoring for GLPI Development Team.

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
   along with Monitoring. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author
   @comment
   @copyright Copyright (c) 2011-2015 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Session::checkRight("plugin_monitoring_command", READ);

Html::header(__('Monitoring - commands', 'monitoring'), $_SERVER["PHP_SELF"], "plugins",
             "PluginMonitoringDashboard", "command");

if (PluginMonitoringConfig::getMonitoringSystem() == 'alignak') {
   echo '<div ng-app="myApp" ng-controller="myCtrl">
<md-content ng-if="displayList">
  <md-toolbar md-scroll-shrink>
    <div class="md-toolbar-tools">{{ui.list_title}}
      <span flex></span>
      <md-button class="md-fab md-mini" aria-label="Add new item" ng-click="addNew()">
        <md-icon md-svg-icon="../pics/ic_add_black_24px.svg"></md-icon>
      </md-button>
      </div>
  </md-toolbar>
   <md-list>
      <md-list-item class="md-3-line" ng-repeat="item in items._items">
        <div class="md-list-item-text" layout="column">
          <h3>{{ item.name }}</h3>
          <h4>{{ item.back_role_super_admin }}</h4>
          <p>{{ item._updated }}</p>
        </div>
        <md-divider ></md-divider>
      </md-list-item>
   </md-list>
</md-content>

<div class="md-inline-form" ng-if="displayForm">
  <md-toolbar md-scroll-shrink>
    <div class="md-toolbar-tools">Add new item</div>
  </md-toolbar>
  <md-content layout-padding>
    <div>
      <form name="userForm" layout="column" layout-wrap layout-gt-sm="row" ng-submit="submit()">

        <div class="inset" ng-repeat="item in schema" ng-if="item.ui.visible && item.name!=\'ui\'" ng-cloak flex-xs flex="33">
            <md-input-container class="md-block" flex="33" ng-if="item.type==\'string\'">
               <label>{{item.ui.title}}</label>
               <input ng-model="answers[item.name]" required />
            </md-input-container>
            <md-switch ng-model="answers[item.name]" aria-label="{{item.ui.title}}" ng-if="item.type==\'boolean\'" flex="33">
               {{item.ui.title}}
            </md-switch>

            <md-input-container class="md-block" flex="33" ng-if="item.type==\'list\'||item.type==\'objectid\'">
               <label>{{item.ui.title}}</label>
               <md-select ng-model="answers[item.name]">
                 <md-option ng-repeat="itemresrouce in resource[item.data_relation.resource]" value="{{itemresrouce._id}}">
                   {{itemresrouce.name}}
                 </md-option>
               </md-select>
            </md-input-container>
            <md-input-container class="md-block" flex="33" ng-if="item.type==\'integer\'">
               <label>{{item.ui.title}}</label>
               <input type="number" step="any" name="rate" ng-model="answers[item.name]" />
            </md-input-container>
        </div>
        <md-button type="submit" class="md-raised md-primary">Create</md-button>
      </form>
    </div>
  </md-content>
</div>

</div>';

} else {
   Search::show('PluginMonitoringCommand');
}
Html::footer();

?>
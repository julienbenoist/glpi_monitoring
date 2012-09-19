Ext.define('GS.store.ServiceRed', {
    extend: 'Ext.data.TreeStore',
    requires: [
       'GS.model.Service',
       'Ext.data.proxy.Rest'
    ],
   id: 'servicered',
   xtype: 'servicered',
    config: {
        autoLoad :true,
        model: 'GS.model.Service',
        clearOnPageLoad:false,
        proxy: {
             type: 'rest',
             url: 'http://192.168.20.194/glpi083/plugins/monitoring/m/app/store/getServices.php?type=PluginMonitoringComponentscatalog&state=red',
             reader: {
                 type: 'json',
                 rootProperty: 'services'
             }
         },
         root: {
            leaf: false
         }         
    }
});
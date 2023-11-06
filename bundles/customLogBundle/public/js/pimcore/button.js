pimcore.registerNS("pimcore.plugin.customLogBundle");

pimcore.plugin.customLogBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // Check if the user has specific permissions
        var user = pimcore.globalmanager.get("user");
        var permissions = user.permissions;

        if (permissions.indexOf("objects") !== -1) {
            var navigationUl = Ext.get(Ext.query("#pimcore_navigation UL"));
            var newMenuItem = Ext.DomHelper.createDom('<li id="pimcore_menu_new-item" data-menu-tooltip="Admin Log" class="pimcore_menu_item icon-book_open"></li>');
            navigationUl.appendChild(newMenuItem);
            pimcore.helpers.initMenuTooltips();
            var iconImage = document.createElement("img");
            iconImage.src = "/bundles/pimcoreadmin/img/flat-white-icons/text.svg";
            newMenuItem.appendChild(iconImage);
            newMenuItem.onclick = function () {
                this.getTabPanel();
            }.bind(this);
        }
    },

    getTabPanel: function () {
        var mainPanel = Ext.getCmp("pimcore_panel_tabs");
        var existingTab = mainPanel.getComponent("adminLogTab");

        if (!existingTab) {
            // Make an AJAX request to the Symfony controller endpoint
            Ext.Ajax.request({
                url: '/admin_log_data', // The URL to your Symfony controller action
                method: 'GET',
                success: function (response, options) {
                    var data = Ext.decode(response.responseText);
                    // Handle the retrieved data and populate your grid or perform other actions.
                    console.log(data);

                    // Create and show your tab panel with the retrieved data
                    // var store = Ext.create('Ext.data.Store', {
                    //     fields: ['id', 'admin_user_id', 'action', 'timestamp', 'controller'],
                    //     data: data.logs // Use the logs data from the AJAX response
                    // });
                    var store = Ext.create('Ext.data.Store', {
                        fields: ['id', 'userid', 'action', 'timestamp'],
                        autoLoad: true,
                        pageSize: 50, // Number of records to display per page (adjust as needed)
                        proxy: {
                            type: 'ajax',
                            url: '/admin_log_data', // Update the URL to match your route
                            reader: {
                                type: 'json',
                                rootProperty: 'logs', // The property containing the data in the JSON response
                                totalProperty: 'total', // The property containing the total count in the JSON response
                            }
                        }
                    });

                    var tabPanel = Ext.create('Ext.panel.Panel', {
                        title: "Admin Log",
                        closable: true,
                        id: 'adminLogTab', // Set the ID for the tab panel
                        layout: 'fit',
                        items: [
                            {
                                xtype: 'grid',
                                store: store,
                                columns: [
                                    { text: "ID", dataIndex: "id" },
                                    { text: "Admin User ID", dataIndex: "userId" },
                                    { text: "Action", dataIndex: "action", flex:2 },
                                    { text: "Timestamp", dataIndex: "timestamp" },
                                    { text: "Controller", dataIndex: "controller" , flex: 3 } // Add a column for "Controller"
                                ],
                                bbar: Ext.create('Ext.PagingToolbar', {
                                    store: store,
                                    displayInfo: true,
                                    displayMsg: 'Displaying {0} - {1} of {2}',
                                    emptyMsg: "No data to display",
                                }),
                                tbar: [
                                    {
                                        text: 'Refresh',
                                        handler: function () {
                                            var grid = tabPanel.down('grid');
                                            var store = grid.getStore();
                                            store.load();
                                        }
                                    }
                                ],
                            }
                        ]
                    });
                    mainPanel.add(tabPanel);
                    mainPanel.setActiveTab(existingTab || tabPanel);
                },
                failure: function (response, options) {
                    console.error('Failed to fetch admin log data');
                }
            });

        }
    }
});

var customLogBundlePlugin = new pimcore.plugin.customLogBundle();

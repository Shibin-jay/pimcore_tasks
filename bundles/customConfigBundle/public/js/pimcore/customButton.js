pimcore.registerNS("customConfigBundle.customMenuPlugin");

customConfigBundle.customMenuPlugin = Class.create({
    systemPanel: null,

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        const user = pimcore.globalmanager.get("user");
        const permissions = user.permissions;
        console.log(permissions);
        console.log("user:",user);

        if (permissions.indexOf("objects") !== -1) {
            const navigationUl = Ext.get(Ext.query("#pimcore_navigation UL"));
            const customMenuItem = Ext.DomHelper.createDom('<li id="pimcore_menu_custom-item" data-menu-tooltip="System" class="pimcore_menu_item icon-server"></li>');
            navigationUl.appendChild(customMenuItem);
            pimcore.helpers.initMenuTooltips();
            const iconImage = document.createElement("img");
            iconImage.src = "/bundles/pimcoreadmin/img/flat-white-icons/survey.svg";
            customMenuItem.appendChild(iconImage);

            const submenu = new Ext.menu.Menu({
                items: [
                    {
                        text: "System Settings",
                        handler: this.toggleSystem.bind(this)
                    },
                    {
                        text: "Option 2",
                    },
                    {
                        text: 'Option 3',
                    }
                ],
                width: 200
            });

            customMenuItem.onclick = function () {
                var customMenuItemPosition = Ext.get(customMenuItem).getXY();
                submenu.showAt([customMenuItemPosition[0] + customMenuItem.offsetWidth, customMenuItemPosition[1]]);
            };
        }
    },

    toggleSystem: function () {
        if (this.systemPanel) {
            const mainPanel = Ext.getCmp("pimcore_panel_tabs");
            mainPanel.setActiveTab(this.systemPanel);
            this.retrieveSavedData();
        } else {
            this.systemPanel = new Ext.Panel({
                title: "System Settings",
                closable: true,
                layout: 'form',
                items: [
                    {
                        xtype: 'fieldset',
                        title: 'Pg Information',
                        collapsible: true,
                        collapsed: true,
                        items: [
                            {
                                xtype: 'combo',
                                fieldLabel: 'Room Type',
                                store: ['Single Room', 'Flat', 'Shared', 'house'],
                                id: 'RoomTypeCombo',
                                allowBlank: false

                            },
                            {
                                xtype: 'combo',
                                multiSelect: true,
                                fieldLabel: 'Pg Locations',
                                store: ['bangalore', 'calicut', 'kannur', 'kochi'],
                                id: 'pgLocTypeCombo',
                                editable: false,
                                allowBlank: false,

                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'PG Name',
                                allowBlank: false,
                                id: 'pgNameTextField'
                            },
                            {
                                xtype: 'textareafield',
                                fieldLabel: 'Description',
                                allowBlank: false,
                                id: 'descriptionTextarea'
                            },
                        ],
                    },
                    {
                        xtype: 'fieldset',
                        title: 'Check', collapsible: true,
                        collapsed: true,
                        items: [
                            {
                                xtype: 'checkbox',
                                boxLabel: 'This is a checkbox field with some content',
                                id: 'checked'
                            },
                        ],
                    },
                    {
                        xtype: 'fieldset',
                        title: 'Contact Details',
                        collapsible: true,
                        collapsed: true,
                        items: [
                            {
                                xtype: "textfield",
                                fieldLabel: 'Email',
                                id : 'emailField',
                                allowBlank : false,
                                vtype: 'email',
                            },
                            {
                                xtype: "textfield",
                                fieldLabel: 'Phone number',
                                id : 'numField',
                                allowBlank: false,
                                maskRe: /[0-9]/,
                                listeners: {
                                    blur : function () {
                                        const numField = Ext.getCmp("numField");
                                        const value = numField.getValue();
                                        console.log(numField);
                                        console.log(value);
                                        console.log(value.length);
                                        if (value.length !== 10) {
                                            numField.markInvalid("Phone number must be 10 digits.");
                                        }
                                    },
                                },
                            },
                        ]
                    },
                ],
                buttons: [
                    {
                        text: t('Save'),
                        iconCls: 'pimcore_icon_save',
                        handler: this.saveData.bind(this),
                    }
                ]
            });

            const mainPanel = Ext.getCmp("pimcore_panel_tabs");
            mainPanel.add(this.systemPanel);
            mainPanel.setActiveTab(this.systemPanel);
            this.retrieveSavedData();
        }

        this.systemPanel.on('beforeclose', function (tab) {
            this.systemPanel = null;
        }.bind(this));
    },
    saveData: function () {
        const RoomType = Ext.getCmp('RoomTypeCombo').getValue();
        const pgLocation = Ext.getCmp('pgLocTypeCombo').getValue();
        const pgName = Ext.getCmp('pgNameTextField').getValue();
        const description = Ext.getCmp('descriptionTextarea').getValue();
        const checkboxValue = Ext.getCmp('checked').getValue();

        const data = {
            RoomType: RoomType,
            PgLocation: pgLocation,
            PgName: pgName,
            description: description,
            checkboxValue: checkboxValue,
        };

        Ext.Ajax.request({
            url: '/custom/save-data',
            method: 'POST',
            params: {
                data: Ext.encode(data)
            },
            success: function (response) {
                try {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        pimcore.helpers.showNotification(t("success"), t("saved_successfully"), "success");

                        Ext.MessageBox.confirm(t("info"), t("reload_pimcore_changes"), function (buttonValue) {
                            if (buttonValue == "yes") {
                                window.location.reload();
                            }
                        }.bind(this));
                    } else {
                        pimcore.helpers.showNotification(t("error"), t("saving_failed"), "error", t(res.message));
                    }
                } catch (e) {
                    pimcore.helpers.showNotification(t("error"), t("saving_failed"), "error");
                    console.log(e);
                }
            }.bind(this)
        });
    },
    retrieveSavedData: function () {
        Ext.Ajax.request({
            url: '/custom/retrieve-saved-data',
            success: function (response) {
                try {
                    var data = Ext.decode(response.responseText);
                    if (data.success) {
                        // Populate the form fields with the retrieved data
                        Ext.getCmp('RoomTypeCombo').setValue(data.data.RoomType);
                        Ext.getCmp('pgLocTypeCombo').setValue(data.data.PgLocation);
                        Ext.getCmp('pgNameTextField').setValue(data.data.PgName);
                        Ext.getCmp('descriptionTextarea').setValue(data.data.description);
                        Ext.getCmp('checked').setValue(data.data.checkboxValue);

                        var savedDataPanel = this.systemPanel.down('#savedDataPanel');
                        savedDataPanel.show(); // Show the "Saved Data" panel
                    }
                } catch (e) {
                    console.error('Error parsing saved data: ' + e);
                }
            }.bind(this)
        });
    },

});

var customMenuPlugin = new customConfigBundle.customMenuPlugin();

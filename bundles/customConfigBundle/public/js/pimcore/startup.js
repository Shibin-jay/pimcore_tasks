pimcore.registerNS("pimcore.plugin.customConfigBundle");

pimcore.plugin.customConfigBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // alert("customConfigBundle ready!");
    }
});

var customConfigBundlePlugin = new pimcore.plugin.customConfigBundle();

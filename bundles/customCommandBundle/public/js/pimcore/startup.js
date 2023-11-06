pimcore.registerNS("pimcore.plugin.customCommandBundle");

pimcore.plugin.customCommandBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // alert("customCommandBundle ready!");
    }
});

var customCommandBundlePlugin = new pimcore.plugin.customCommandBundle();

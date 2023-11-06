pimcore.registerNS("pimcore.plugin.customLogBundle");

pimcore.plugin.customLogBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // alert("customLogBundle ready!");
    }
});

var customLogBundlePlugin = new pimcore.plugin.customLogBundle();

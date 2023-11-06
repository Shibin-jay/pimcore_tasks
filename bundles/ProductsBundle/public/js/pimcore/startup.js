document.addEventListener(pimcore.events.postOpenObject, (e) => {
    if (e.detail.object.data.general.className === 'products') {
        e.detail.object.toolbar.add({
            text: t('Preview'),
            iconCls: 'pimcore_icon_preview',
            scale: 'small',
            handler: function (obj) {
                const objectId = obj.id;
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `/products/id=${objectId}`, true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = xhr.responseText;

                        const newWindow = window.open(`/products/id=${objectId}`,'_blank');
                        newWindow.document.write(response);
                        newWindow.location.href= '/products/id='+objectId;
                    } else if (xhr.readyState === 4) {
                        console.error('AJAX request failed.');
                    }
                };
                console.log("sent 2 successfully");
                xhr.send();
            }.bind(this, e.detail.object)
        });

        pimcore.layout.refresh();
    }
});


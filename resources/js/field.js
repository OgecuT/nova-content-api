Nova.booting((Vue, router, store) => {
    Vue.component('custom-index-header', require('./components/CustomIndexHeader'));
    Vue.component('create-resource-button', require('./components/CreateResourceButton'));

    //Vue.component('custom-index-toolbar', require('./components/CustomIndexToolbar'));
});

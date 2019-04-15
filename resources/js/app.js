
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
// require ('vue-tribute/dist/vue-tribute');

window.Vue = require('vue');

let authorizations = require('./authorization');

window.Vue.prototype.authorize = function(...params) {
    if (!window.App.user) return false;

    if (typeof params[0] == 'string') {
        return authorizations[params[0]](params[1]);
    }

    return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;

window.events = new Vue;

window.flash = function(message, type = 'success') {
    window.events.$emit('flash', { message, type });
};

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);
Vue.component('avatar-form', require('./components/AvatarForm.vue').default);
Vue.component('thread-view', require('./views/Thread.vue').default);
Vue.component('search-view', require('./views/Search.vue').default);

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app'
});

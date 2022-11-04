/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import Maska from 'maska'
Vue.use(Maska)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
/*Componentes aqui!*/
Vue.component('alertComponent', require('./components/elements/Alert.vue').default);
Vue.component('inputComponent', require('./components/elements/Input.vue').default);
Vue.component('tableComponent', require('./components/elements/Table.vue').default);
Vue.component('buttonComponent', require('./components/elements/Buttom.vue').default);
Vue.component('formComponent', require('./components/elements/Form.vue').default);
Vue.component('containerComponent', require('./components/elements/Container.vue').default);
Vue.component('paginacaoComponent', require('./components/elements/Paginacao.vue').default);
Vue.component('modalComponent', require('./components/elements/Modal.vue').default);

/*Telas aqui!*/
Vue.component('Contas', require('./components/app/Contas.vue').default);
Vue.component('Login', require('./components/app/Login.vue').default);
Vue.component('Registrar', require('./components/app/Registrar.vue').default);
Vue.component('Email', require('./components/app/Email.vue').default);
Vue.component('Home', require('./components/app/Home.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 
const app = new Vue({
    el: '#app',
});

require('./bootstrap');

window.Vue = require('vue').default;
import Maska from 'maska'
import VueSweetalert2 from 'vue-sweetalert2';
import Velocity from 'velocity-animate';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);
Vue.use(Maska)

Vue.component('alertComponent', require('./components/elements/Alert.vue').default);
Vue.component('inputComponent', require('./components/elements/Input.vue').default);
Vue.component('tableComponent', require('./components/elements/Table.vue').default);
Vue.component('buttonComponent', require('./components/elements/Buttom.vue').default);
Vue.component('formComponent', require('./components/elements/Form.vue').default);
Vue.component('containerComponent', require('./components/elements/Container.vue').default);
Vue.component('paginacaoComponent', require('./components/elements/Paginacao.vue').default);
Vue.component('modalComponent', require('./components/elements/Modal.vue').default);

/*Telas aqui!*/
Vue.component('Login', require('./components/app/Login.vue').default);
Vue.component('Email', require('./components/app/Email.vue').default);
Vue.component('Home', require('./components/app/Home.vue').default);
Vue.component('Redefinirsenha', require('./components/app/RedefinirSenha.vue').default);
Vue.component('Verificar', require('./components/app/Verificar.vue').default);
//Form
Vue.component('Contas', require('./components/app/Contas.vue').default);
Vue.component('Registrar', require('./components/app/Registrar.vue').default);
 
const app = new Vue({
    el: '#app',
});

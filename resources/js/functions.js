import VueTheMask from 'vue-the-mask'
import {VMoney} from 'v-money'
// import VueSwal from 'vue-swal'
import Velocity from 'velocity-animate';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueTheMask)
Vue.use(VueSweetalert2);

export default {
    data () {
        return {
            money: {
                decimal: ',',
                thousands: '.',
                precision: 2,
                masked: false
            }
        }
    },
    directives: {money: VMoney},
    methods: {
        GetUrl(api = true) {
            if (!api) {
                return location.href
            }
            return location.origin+"/api"+location.pathname
        },
        FormataValor(val) {
            if (val) {
                if (!isNaN(val.toString().substr(0, 1)) && val.toString().indexOf("-") !== -1) {
                    val = val.split('-').reverse().join('/');
                }
                if (!isNaN(val.toString().substr(0, 1)) && val.toString().indexOf(".") !== -1) {
                    val.trim()
                    val = parseFloat(val).toLocaleString('pt-BR', {
                        style: "currency",
                        currency: 'BRL'
                    }).substr(3)
                }
            }
            
            return val
        },
        FormataValorInputBanco(valor) {
            if (valor) {
                if (!isNaN(valor.substr(0, 1)) && valor.indexOf(",") !== -1) {
                    if (valor.toString().indexOf(',') !== -1) {
                        valor = valor.replace(/\./g, '').replace(/\,/g, '.');
                    }
                    valor = Number(valor);
                }
            }
            return valor;
        },
    },
    directives: {
        money: VMoney
    }
}
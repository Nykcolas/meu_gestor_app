import axios from "axios";
import functions from "./functions";

export default {
    data() {
        return {
            dataRota: {},
            acao: "",
        }
    },
    methods: {
        Create: async function(url) {

            for (const key in FormSubmit.firstElementChild.children) {
                if (FormSubmit.firstElementChild.children[key].lastChild) {
                    let valor = FormSubmit.firstElementChild.children[key].lastChild.value
                    FormSubmit.firstElementChild.children[key].lastChild.value = this.FormataValorInputBanco(valor)
                }
            }

            let id = FormSubmit.getAttribute('data-id');
            
            let formData = new FormData(FormSubmit);
            if(this.acao == "U") {
                url = `${url}/${id}`
                formData.append('_method', 'put');
            }

            const config = {
                headers: {
                    'Accept': 'application/json',
                }
            }
            
            let ax = null;
            await axios.post(url, formData, config).then(response => {
                if (response.token) {
                    document.cookie = 'token='+response.token+';SameSite=Lax'
                }

                if (!this._events.Finaly) {
                    if (response.data.message) {
                        Vue.swal(response.statusText, response.data.message, 'success').then(function(confirm) {
                            if (confirm.isConfirmed) {
                                location.reload();
                            }
                        })
                    } else {
                        document.location.reload();
                    }
                }
                ax = response;
            }).catch(errors => {
                this.detalheStatus = 'danger';
                this.mensagemDados = {
                    titulo: this.TraduzHTTPCode(errors.response.status),
                    mensagem: errors.response.data.errors
                }
                
                ax = errors;
            }).finally(() => this.$emit('Finaly', ax));
            
        },
        GetDadosRota: async function(id = null, urlNew = null) {
            const config = {
                headers: {
                    'Accept': 'application/json',
                }
            }
            let url = urlNew;
            if (urlNew === null) {
                url = id != null ? `${this.GetUrl()}/${id}`: this.GetUrl();
            }
            
            await axios.get(url, config)
                .then(response => {
                    this.arrDadosRota = response.data
                    this.dataRota = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
        },
        Read: async function(id) {
            await this.GetDadosRota(id);
            this.AjustaCamposAcao("R");
        },
        Delete(id) {
            Vue.swal({
                title: 'Tem certeza?',
                text: "Você não será capaz de reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2aa198',
                cancelButtonColor: '#da292d',
                confirmButtonText: 'Sim, exclua!',
                cancelButtonText: 'Cancelar'
            })
            .then(function (confirm) {
                if(confirm.isConfirmed) {
                    let formData = new FormData();
                    formData.append('_method', 'delete');
                    let config = {
                        headers: {
                            'Accept': 'application/json',
                        }
                    }

                    let url = `${location.origin+"/api"+location.pathname}/${id}`;
                    axios.post(url, formData, config)
                        .then(response => {
                            document.location.reload();
                        })
                        .catch(errors => {
                            console.log(errors);
                        });
                }
            });
        },
        Update: async function(id) {
            await this.GetDadosRota(id);
            this.AjustaCamposAcao("U");
        },
        AjustaAcao: async function(acao) {
            if (this.acao == "T" || this.acao == "") {
                this.acao = acao;
            } else {
                this.acao = "T";
                this.detalheStatus = "";
            }
            
            this.AjustaCamposAcao();
        
            return this.acao;
        },
        AjustaCamposAcao: async function(acao = null) {
            FormSubmit.removeAttribute('data-id');
            for (const key in FormSubmit.firstElementChild.children) {
                let input = FormSubmit.firstElementChild.children[key].lastChild
                if (input) {
                    if (input.nodeName == "INPUT" || input.nodeName == "SELECT") {
                        input.removeAttribute('disabled');
                        if (input.name in this.dataRota) {
                            input.value = await input.type != 'date'?this.FormataValor(this.dataRota[input.name]): this.dataRota[input.name];
                            if (acao == "R") {
                                input.setAttribute('disabled', '');
                            }
                            if (acao == "U") {
                                FormSubmit.setAttribute('data-id', this.dataRota["id"]);
                            }
                        }
                    }
                }
            }
        }
    },
    mixins: [functions]
}
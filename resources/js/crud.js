import axios from "axios";
import functions from "./functions";

export default {
    data() {
        return {
            dataRota: {}
        }
    },
    methods: {
        Create(url) {

            for (const key in FormSubmit.firstElementChild.children) {
                if (FormSubmit.firstElementChild.children[key].lastChild) {
                    let valor = FormSubmit.firstElementChild.children[key].lastChild.value
                    FormSubmit.firstElementChild.children[key].lastChild.value = this.FormataValorInputBanco(valor)
                }
            }
            
            let formData = new FormData(FormSubmit);
            if(this.$store.state.acao == "U") {
                url = `${url}/${this.$store.state.id}`
                formData.append('_method', 'put');
            }

            const config = {
                headers: {
                    'Accept': 'application/json',
                }
            }
            setTimeout(() => {
                axios.post(url, formData, config).then(response => {
                    if (response.token) {
                        document.cookie = 'token='+response.token+';SameSite=Lax'
                    }
                    document.location.reload();
                }).catch(errors => {
                    this.detalheStatus = 'danger';
                    this.mensagemDados = {
                        titulo: errors.response.statusText,
                        mensagem: errors.response.data.errors
                    }     
                });
            }, 500);
        },
        GetDadosRota(id = null, urlNew = null) {
            const config = {
                headers: {
                    'Accept': 'application/json',
                }
            }
            let url = urlNew;
            if (urlNew === null) {
                url = id != null ? `${this.GetUrl()}/${id}`: this.GetUrl();
            }
            
            axios.get(url, config)
                .then(response => {
                    this.arrDadosRota = response.data
                    this.dataRota = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
        },
        Read(id) {
            this.GetDadosRota(id);
            this.AjustaAcao("R");
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
        Update(id) {
            this.$store.state.id = id;
            this.GetDadosRota(id);
            this.AjustaAcao("U");
        },
        AjustaAcao(acao) {
            if (this.$store.state.acao == "T" || this.$store.state.acao == "") {
                this.$store.state.acao = acao;
            } else {
                this.$store.state.acao = "T";
            }
            setTimeout(() => {
                this.AjustaCamposAcao(acao);
            }, 1000);
        },
        AjustaCamposAcao(acao) {
            for (const key in FormSubmit.firstElementChild.children) {
                let input = FormSubmit.firstElementChild.children[key].lastChild
                if (input) {
                    if (input.nodeName == "INPUT") {
                        input.removeAttribute('disabled');
                        if (this.dataRota[input.name]) {
                            input.value = input.type != 'date'?this.FormataValor(this.dataRota[input.name]): this.dataRota[input.name];
                            if (acao == "R") {
                                input.setAttribute('disabled', '');
                            }
                        }
                    }
                }
            }
        }
    },
    mixins: [functions]
}
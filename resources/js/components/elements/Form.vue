<template>
    <containerComponent :comprimento="comprimento">
        <template v-slot:header>
            {{titulo}}
            <buttonComponent 
            @click="GetAcaoAtual('C')" 
            type="primary" 
            style="float: right;" 
            v-if="permissoes.c">
            <template v-slot:label>
                <span v-if="labelButton">{{labelButton}}</span>
                <span v-else-if="acaoAtual == 'T'">Adicionar</span>
                <span v-else>Voltar</span>
            </template>
            </buttonComponent>
        </template>
        <template v-slot:body>
            <div class="content-crud">
                <transition name="toggle">
                    <div class="form" v-show="acaoAtual != 'T' && acaoAtual != ''">
                        <form method="post" id="FormSubmit" @submit.prevent="Create(url || GetUrl(api), $event)">
                            <transition name="slide">
                                <alertComponent :Type="detalheStatus" :titulo="mensagemDados.titulo" :mensagem="mensagemDados" v-if="detalheStatus != ''"></alertComponent>
                            </transition>
                            <div class="row align-items-center">
                                <input type="hidden" name="_token" :value="token">
                                <slot name="form"></slot>
                            </div>
                            <buttonComponent v-if="acaoAtual != 'R'" type="primary" style="float: right;">
                                <template v-slot:label>
                                    <span v-if="labelButton">{{labelButton}}</span>
                                    <span v-else-if="acaoAtual == 'C'">Adicionar</span>
                                    <span v-else>Atualizar</span>
                                </template>
                            </buttonComponent>
                        </form>
                    </div>
                </transition>
                <transition name="toggle">
                    <div class="table" v-if="acaoAtual == 'T'">
                        <tableComponent @EmitEventoForm="GetAcaoAtual" :permissoes="permissoes" :colums="colums" :data="arrDadosRota">
                            <template v-slot:paginas>
                                <li  v-for="(item, index) in arrDadosRota.links" :key="index" :class="item.active?'page-item active':'page-item'"><a @click="AjustaPaginacao(item)" class="page-link" v-html="item.label"></a></li>
                            </template>
                        </tableComponent>
                    </div>
                </transition>
            </div>
        </template>
    </containerComponent>
</template>
<script>
import crud from '../../crud';
export default {
    props: ["titulo", "api", "url", "comprimento", "token", "labelButton", "permissao", "tipoAcao", "colums"],
    data() {
        return {
            detalheStatus: '',
            mensagemDados: {},
            permissoes: {},
            arrDadosRota: {},
            acaoAtual: "",
        }
    },
    methods: {
        AjustaPaginacao(dadosPaginacao) {
            if (dadosPaginacao.url) {
                this.GetDadosRota(null, dadosPaginacao.url);
            }
        },
        ValidaPermissao() {
            this.permissoes = {c: true, r: true, u: true, d: true, s: true}
            for(let name in this.permissao) {
                this.permissoes[name] = this.permissao[name];
            }
        },
        GetAcaoAtual(acao) {
            this.AjustaAcao(acao).then(result => {
                this.acaoAtual = result;
            });
        }
    },
    mounted() {
        this.GetAcaoAtual(this.tipoAcao ?? "T");
        this.ValidaPermissao()
        if ((this.tipoAcao || "T") === "T") {
            this.GetDadosRota(null, this.url ?? this.GetUrl(this.api))
        }
    },
    mixins: [crud]


}
</script>
<style>
    .content-crud {
        position: relative;
        padding-right: 10px;
    }

    .toggle-enter-active {
        animation: toggle-in ease-in-out 1300ms;
    }
    .toggle-leave-active {
        animation: toggle-in ease-in-out 300ms reverse;
    }

    @keyframes toggle-in {
        0% {
            opacity: 0;
            transform: translateX(-4rem);
        }
        100% {
            opacity: 1;
            transform: translate(0);
        }
    }

    .slide-enter-active,
    .slide-leave-active {
        animation: slide-in ease 500ms;
    }

    .slide-enter-from,
    .slide-leave-to {
        animation: slide-out ease 500ms;
    }

    @keyframes slide-in {
        from {
            transform: translateY(4rem);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slide-out {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(4rem);
            opacity: 0;
        }
    }

</style>
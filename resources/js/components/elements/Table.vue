<template>
    <div id="table-total">
        <inputComponent v-if="data.links" placeholder="Pesquisar" largura="2" name="nome_conta" :value="pesquisa" type="text" @input="Pesquisar($event)"></inputComponent>
        <transition name="slide">
            <table v-show="lsitaFiltrada !== undefined" class="table table-bordered table-striped table-hover table-responsive-sm">
                <thead>
                    <tr>
                        <th v-for="(name, index) in colums" :key="index">{{verificaColums(name)}}</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <transition-group 
                    tag="tbody" 
                    name="staggered-fade" 
                    @before-enter="beforeEnter"
                    @enter="enter"
                    @leave="leave"
                    v-if="this.lsitaFiltrada && this.lsitaFiltrada.length > 0"
                >
                    <tr v-for="(arr, tr) in lsitaFiltrada" :key="lsitaFiltrada[tr].id">
                        <td v-for="(name, index) in colums" :align="arr[index] && (((!isNaN(arr[index].toString().substr(0, 1))) && arr[index].toString().indexOf('.') !== -1)) || !isNaN(arr[index]) ?'right':''" :key="index">{{AjustaValorTable(name, arr[index])}}</td>
                        <td style="width: 1rem;">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <buttonComponent v-if="permissoes.u" label="Atualizar" type="primary" @click="EmitEventoForm('U'), Update(arr['id'])"></buttonComponent>
                                <buttonComponent v-if="permissoes.r" label="Vizualizar" type="warning" @click="EmitEventoForm('R'), Read(arr['id'])"></buttonComponent>
                                <buttonComponent v-if="permissoes.d" label="Deletar" type="danger" @click="Delete(arr['id'])"></buttonComponent>
                                <slot name="action-table" :id="arr['id']"></slot>
                            </div>
                        </td>
                    </tr>
                </transition-group>
                <transition v-else name="slide">
                    <tbody>
                        <tr><td :colspan="Object.keys(colums).length + 1" align="center"><strong>Nem um registro encontrado</strong></td></tr>
                    </tbody>
                </transition>
            </table>
        </transition>
        <paginacaoComponent v-if="data.links" :dados-paginados="data.links">
            <template v-slot:paginas>
                <slot name="paginas"></slot>
            </template>
        </paginacaoComponent>
    </div>
</template>
<script>
import crud from '../../crud';
export default {

    props: ["data", "colums", 'permissoes'],
    data() {
        return {
            pesquisa:"",
            lsitaFiltrada: []
        }
    },
    methods: {
        verificaColums(colums) {
            if (typeof colums === 'object' && !Array.isArray(colums) && colums !== null){
                return colums.name
            }
            return colums;
        },
        EmitEventoForm(acao) {
            this.$emit("EmitEventoForm", acao);
        },
        beforeEnter: function (el) {
            el.style.transform = "translateY(15rem)"
            el.style.transition = "ease 500ms"
            el.style.opacity = 0
        },
        enter: function (el, done) {
            var delay = el.getBoundingClientRect().top * 1.5
            
            setTimeout(function () {
                Velocity(
                    el,
                    { transform: "translateY(0)", opacity: 1},
                    { complete: done }
                )
            }, delay)
        },
        leave: function (el, done) {
            var delay = el.getBoundingClientRect().top * -1.5
            setTimeout(function () {
                Velocity(
                    el,
                    { transform: "translateY(15rem)", opacity: 0},
                    { complete: done }
                )
            }, delay)
        },
        Pesquisar(event) {
            this.pesquisa = event.target.value;
        },
        AjustaValorTable(th, td) {
            if (typeof th === 'object' && !Array.isArray(th) && th !== null){
                return th.options[td];
            }
            return this.FormataValor(td);
        }
    },
    computed: {
        FiltraLista() {
            let data;
            if(this.data.data){
                for (const colum in this.colums) {
                    if(colum.indexOf("nome") !== -1){
                        data = this.data.data.filter(Lista => {
                            if(Lista[colum]){
                                if (Lista[colum].toString().toLowerCase().indexOf(this.pesquisa.toLowerCase()) !== -1) {
                                    return Lista;
                                }
                            }
                        });
                    }
                }
                return data;
            }
        }
    },
    beforeUpdate() {
        this.lsitaFiltrada = this.FiltraLista            

    },
    beforeMount() {
        this.lsitaFiltrada = this.FiltraLista
    },
    mixins: [crud],

}
</script>
<style scoped>
.table-total {
    display: flex;
    flex-direction: column;
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
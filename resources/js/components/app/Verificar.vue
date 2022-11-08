<template>
    <formComponent url="/email/resend" @Finaly="MostraMensagemEnviado" :api="false" tipo-acao="C" :token="token" titulo="Verifique seu endereço de e-mail" comprimento="8" label-button="clique aqui para solicitar outro e-mail" :permissao="permissao">
        <template v-slot:form>
            <transition name="slide">
                <alertComponent :Type="'success'" :titulo="'Solicitação Aceita'" :texto="'Um novo link de verificação foi enviado para seu endereço de e-mail.'" v-if="mostraMensagem"></alertComponent>
            </transition>
            <p>Antes de prosseguir, por favor, veja se recebeu o link de verificação em seu e-mail </p>
        </template>
    </formComponent>
</template>

<script>
    export default {
        props: ['token'],
        methods: {
            MostraMensagemEnviado(data) {
                this.mostraMensagem = data.status == 202
            },
        },
        data() {
            return {
                permissao: {c: false, r: false, u: false, d: false, s: false},
                mostraMensagem:false
            }
        },
    }
</script>
<style>
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
<template>
    <formComponent :url="''" :api="false" tipo-acao="C" :token="token" titulo="Redefinir Senha" comprimento="8" label-button="Redefinir Senha" :permissao="permissao">
        <template v-slot:form>
            <inputComponent largura="12" label="Endereço E-mail" name="email" type="email" :value="email"></inputComponent>
            <inputComponent largura="12" label="Senha" name="password" type="password"></inputComponent>
            <inputComponent largura="12" label="Senha" name="password_confirmation" type="password"></inputComponent>
        </template>
    </formComponent>
</template>

<script>
    export default {
        props: ['token'],
        data() {
            return {
                permissao: {c: false, r: false, u: false, d: false, s: false},
                email: ""
            }
        },
        mounted() {
            const searchURL = new URL(window.location);
            this.email = searchURL.searchParams.get('email')
        },
    }
</script>
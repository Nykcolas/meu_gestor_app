<template>
    <formComponent :api="false" tipo-acao="C" :token="token" titulo="Login" comprimento="8" label-button="Login" :permissao="permissao">
        <template v-slot:form>
            <inputComponent largura="12" label="Endereço E-mail" name="email" type="email"></inputComponent>
            <inputComponent largura="12" label="Senha" name="password" type="password"></inputComponent>
            <inputComponent class="ms-3" label="Mantenha-me Conectado" value="1" name="remember" type="checkbox"></inputComponent>
            <a class="btn mb-3 btn-link col-auto" href="password/reset">Esqueci a Senha</a>
        </template>
    </formComponent>
</template>

<script>
    export default {
        props: ['token'],
        data() {
            return {
                permissao: {c: false, r: false, u: false, d: false, s: false}
            }
        },
    }
</script>
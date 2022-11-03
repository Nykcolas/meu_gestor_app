<template>
    <formComponent :api="false" tipo-acao="C" :token="token" titulo="Registre-se" comprimento="8" label-button="Registre-se" :permissao="permissao">
        <template v-slot:form>
            <inputComponent largura="8" label="Nome" name="name"></inputComponent>
            <inputComponent largura="8" label="Endereço E-mail" name="email" type="email"></inputComponent>
            <inputComponent largura="8" label="Senha" name="password" type="password"></inputComponent>
            <inputComponent largura="8" label="Confirmação de senha" name="password_confirmation" type="password"></inputComponent>
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
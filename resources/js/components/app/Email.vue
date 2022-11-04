<template>
    <formComponent :url="'email'" :api="false" tipo-acao="C" :token="token" titulo="Modificar Senha" comprimento="8" label-button="Enviar link para redefinir senha" :permissao="permissao">
        <template v-slot:form>
            <inputComponent largura="12" label="Endereço E-mail" name="email" type="email"></inputComponent>
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
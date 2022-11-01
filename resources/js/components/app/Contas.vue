<template>
    <formComponent :token="token" :colums="colums" comprimento="12" titulo="Contas">
        <template v-slot:form>
            <inputComponent label="Nome da Conta" name="nome_conta" type="text"></inputComponent>
            <inputComponent label="Valor pago por mês" name="valor_pago_mensal" mascara="decimal" type="text"></inputComponent>
            <inputComponent label="Data de inicio" name="data_inicio_conta"  type="date"></inputComponent>
            <inputComponent label="Dia do Vencimento" name="dia_vencimento" maxlength="2" mascara="inteiro"></inputComponent>
        </template>
    </formComponent>
</template>

<script>
export default {
    props: ['token'],
    data() {
        return {
            colums: {
                nome_conta:'Contas',
                valor_pago_mensal:'Valor por mês', 
                data_inicio_conta:'Iniciou os pagamentos', 
                dia_vencimento:'Dia do Vencimento'
            }
        }
    }
}
</script>
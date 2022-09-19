<template>
    <containerComponent comprimento="8">
        <template v-slot:header>
            Painel de controle
        </template>
        <template v-slot:body>
            <alertComponent Type="success" texto="Você está Logado!"></alertComponent>
        </template>
    </containerComponent>
</template>
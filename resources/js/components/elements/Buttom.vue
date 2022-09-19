<template>
    <button 
        @click="ChamaEmissao" 
        :id="id" 
        :class="'btn btn-sm btn-'+type" 
        :type="btn"
        v-bind="$attrs"
    >
        <a class="button-link" :href="link" v-if="link && label">{{label}}</a>
        <span v-else-if="label">{{label}}</span>
        <slot name="label"></slot>
    </button>
</template>
<script>
export default {
    props: ["label", "type", "btn", 'id', 'link'],
    methods: {
        ChamaEmissao() {
            this.$emit('click');
        }
    },
}
</script>
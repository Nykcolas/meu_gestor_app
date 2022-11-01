<template>
    <div :class="divClasse + comprimento">
        <label v-if="label" :class="labelClasse" :for="name">{{label}}</label>
        <input :type="type" :name="name" @input="$emit('input', $event)" :class="classe" v-maska="formato" v-bind="$attrs" :value="value" :id="name" :placeholder="placeholderInput">
    </div>
</template>

<script>
import functions from '../../functions';
export default {
    props: [
        "label", 
        "name",
        "type",
        "placeholder",
        "classInput",
        "labelClass",
        "divClass",
        "largura",
        "mascara",
        "value"
    ],
    data() {
        return {
            classe: this.classInput ?? this.type == 'checkbox' ? "form-check-input" : "form-control form-control-sm",
            placeholderInput: this.placeholder ?? " ",
            labelClasse: this.labelClass ?? (this.type == 'checkbox' ? "form-check-label" : "col-md-12 col-form-label"),
            divClasse: this.divClass ?? (this.type == 'checkbox' ? 'form-check mb-3' : "mb-3"),
            comprimento: this.largura ? " col-md-" + this.largura : " col-auto",
            formato: this.GetFormato()
        }
    },
    methods: {
        GetFormato() {
            var formato = "";
            switch (this.mascara) {
                case 'decimal':
                    formato = [
                        "#,##", 
                        '##,##',
                        '###,##', 
                        '#.###,##', 
                        '##.###,##', 
                        '###.###,##',
                        '#.###.###,##',
                        '##.###.###,##',
                        '###.###.###,##',
                        '#.###.###.###,##',
                        '##.###.###.###,##',
                        '###.###.###.###,##',
                    ]
                    break;
                case 'cpf':
                    formato = '###.###.###-##'
                    break;
                case 'cnpj':
                    formato = '##.###.###/####-##'
                    break;
                case 'inteiro':
                    formato = [              
                        "#", 
                        '##',
                        '###', 
                        '#.###', 
                        '##.###', 
                        '###.###',
                        '#.###.###',
                        '##.###.###',
                        '###.###.###',
                        '#.###.###.###',
                        '##.###.###.###',
                        '###.###.###.###',
                    ]
                    break;
                default:
                    formato = null;
                    break;
            }
            return formato;
        }
    },
    mixins: [functions],
}
</script>
<template>
    <div :class="divClasse + comprimento">
        <label v-if="label" :class="labelClasse" :for="name">{{label}}</label>
        <input 
            v-if="!options"
            :type="tipo" 
            :name="name" 
            @input="$emit('input', $event)"
            :class="classe"
            v-maska="formato" 
            v-bind="$attrs"
            :value="value"
            :id="name" 
            :placeholder="placeholderInput"
        >
        <select 
            v-else
            :name="name" 
            :id="name"
            @input="$emit('input', $event)"
            v-bind="$attrs"
            class="form-select form-select-sm"
        >
            <option value="" selected>Selecione:</option>
            <option v-for="(text, value) in options" :key="text" :value="value">{{text}}</option>
        </select>
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
        "value",
        'options'
    ],
    data() {
        return {
            tipo: this.type ?? 'text',
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
<template>
    <div :class="divClasse + comprimento">
        <label v-if="label" :class="labelClasse" :for="name">{{label}}</label>
        <!-- input comum -->
        <input
            v-if="!options"
            :type="tipo" 
            :name="name" 
            :class="classe"
            v-maska="formato" 
            v-bind="$attrs"
            :value="value"
            :id="name" 
            :placeholder="placeholder"

            @paste="$emit('paste', $event)"
            @focusin="$emit('focusin', $event)"
            @focusout="$emit('focusout', $event)"
            @focus="$emit('focus', $event)"
            @input="$emit('input', $event)"
            @change="$emit('change', $event)"
            @keydown="$emit('keydown', $event)"
            @keyup="$emit('keyup', $event)"
            @click="$emit('click', $event)"
        >
        <!-- Select list Multiplo -->
        <selectMultipeComponent 
            v-else-if="'multiple' in $attrs" 
            :name="name" 
            :id="name"
            :value="value || stateValue"
            :placeholder="placeholderInput"
            :options="options"
            :openDirection="direcao" 
            mode="multiple"
            :hideSelected="false"
            :close-on-select="false"
            :searchable="pesquisa"
            :nativeSupport="true"
            :disabled="stateDisabled"

            @paste="$emit('paste', $event)"
            @open="$emit('open', $event)"
            @close="$emit('close', $event)"
            @select="$emit('select', $event)"
            @deselect="$emit('deselect', $event)"
            @input="$emit('input', $event)"
            @search-change="$emit('search-change', $event)"
            @tag="$emit('tag', $event)"
            @option="$emit('option', $event)"
            @update:modelValue="$emit('modelValue', $event)"
            @change="$emit('change', $event)"
            @clear="$emit('clear', $event)"
            @keydown="$emit('keydown', $event)"
            @keyup="$emit('keyup', $event)"
        >
            <template v-slot:multiplelabel="{values}">
                {{values.length}} de {{Object.keys(options).length}}
            </template>
            <template v-slot:beforelist="{options}">
                <slot name="beforelist" :options="options"></slot>
            </template>
            <template v-slot:afterlist="{options}">
                <slot name="afterlist" :options="options"></slot>
            </template>
        </selectMultipeComponent>
        <!-- Select list comum -->
        <selectMultipeComponent 
            v-else
            :name="name" 
            :id="name" 
            :value="value || stateValue" 
            :placeholder="placeholderInput"
            :options="options"
            :searchable="pesquisa"
            :openDirection="direcao"
            :nativeSupport="true"
            :disabled="stateDisabled"

            @paste="$emit('paste', $event)"
            @open="$emit('open', $event)"
            @close="$emit('close', $event)"
            @select="$emit('select', $event)"
            @deselect="$emit('deselect', $event)"
            @input="$emit('input', $event)"
            @search-change="$emit('search-change', $event)"
            @tag="$emit('tag', $event)"
            @option="$emit('option', $event)"
            @update:modelValue="$emit('modelValue', $event)"
            @change="$emit('change', $event)"
            @clear="$emit('clear', $event)"
            @keydown="$emit('keydown', $event)"
            @keyup="$emit('keyup', $event)"
        >
        </selectMultipeComponent>
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
        'options',
        'pesquisa',
        'direcao' //top|bottom
    ],
    emits: [
        'paste', 'open', 'close', 'select', 'deselect', 
        'input', 'search-change', 'tag', 'option', 'update:modelValue',
        'change', 'clear', 'keydown', 'keyup',
    ],
    data() {
        return {
            tipo: this.type ?? 'text',
            classe: this.classInput ?? this.type == 'checkbox' ? "form-check-input" : "form-control form-control-sm",
            placeholderInput: this.placeholder ?? "Selecione:",
            labelClasse: this.labelClass ?? (this.type == 'checkbox' ? "form-check-label" : "col-md-12 col-form-label"),
            divClasse: this.divClass ?? (this.type == 'checkbox' ? 'form-check mb-3' : "mb-3"),
            comprimento: this.largura ? " col-md-" + this.largura : " col-auto",
            formato: this.GetFormato(),
            stateDisabled: false,
            stateValue: null,
        }
    },
    methods: {
        teste(){console.log('teste')},
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
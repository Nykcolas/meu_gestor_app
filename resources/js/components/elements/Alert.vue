<template>
    <div>
        <div :class="'alert alert-'+Type" role="alert">
            <h4 class="alert-heading" v-if="titulo">{{titulo}}</h4>

            <hr v-if="mensagem && mensagem.mensagem">
            <transition-group 
                tag="ul" 
                v-if="mensagem && mensagem.mensagem"
                name="staggered-fade" 
                @before-enter="beforeEnter"
                @enter="enter"
                @leave="leave"
            >
                <li v-for="(item, key) in mensagem.mensagem" :key="mensagem.mensagem[key][0]">
                    <strong v-for="sub in item" :key="sub">{{ sub }}<br></strong>
                </li>
            </transition-group>
            <hr v-if="texto && mensagem">
            <span v-if="texto">{{texto}}</span>
        </div>
    </div>
</template>
<script>
export default {
    props: ["Type", "mensagem", 'titulo', 'texto'],
    methods: {
        beforeEnter: function (el) {
            el.style.transform = "translateX(15rem)"
            el.style.transition = "ease 500ms"
            el.style.opacity = 0
        },
        enter: function (el, done) {
            var delay = el.getBoundingClientRect().top * 1.5
            
            setTimeout(function () {
                Velocity(
                    el,
                    { translateX: "0", opacity: 1},
                    { complete: done }
                )
            }, delay)
        },
        leave: function (el, done) {
            var delay = el.getBoundingClientRect().top
            setTimeout(function () {
                Velocity(
                    el,
                    { translateX: '-15rem', opacity: 0},
                    { complete: done }
                )
            }, delay)
        },
    },
}
</script>
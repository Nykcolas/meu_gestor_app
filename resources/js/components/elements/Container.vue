<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :class="largura">
                <div class="card">
                    <div class="card-header">
                        <slot name="header"></slot>
                    </div>
                    <div class="card-body">
                        <slot name="body"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["comprimento"],
    data() {
        return {
            "largura": this.comprimento ? "col-md-"+this.comprimento: "col-auto"
        }
    },
}
</script>
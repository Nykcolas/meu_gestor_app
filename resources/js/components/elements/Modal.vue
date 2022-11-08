<template>
    <div class="modal fade" :id="idmodal" tabindex="-1" :aria-labelledby="idmodal+'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="idmodal+'Label'">{{modaltitulo}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot name="modalbody"></slot>
                </div>
                
                <div v-if="footer" class="modal-footer">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['idmodal', 'modaltitulo', 'footer'],
}
</script>
<!-- data-bs-toggle="modal" data-bs-target="#exampleModal"  -->
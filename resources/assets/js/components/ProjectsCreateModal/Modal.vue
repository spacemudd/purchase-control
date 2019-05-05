<template>
    <b-modal :active="open" @close="close">
        <projects-create-modal-form></projects-create-modal-form>
    </b-modal>
</template>

<script>
    export default {
        computed: {
            open: {
                get() {
                    return this.$store.getters['Project/showNewModal'];
                }
            },
        },
        methods: {
            close() {
                this.$store.commit('Project/setNewModal', false);
            },
        }
    }
</script>

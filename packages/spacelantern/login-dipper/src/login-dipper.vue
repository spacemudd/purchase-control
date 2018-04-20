<template>
    <b-modal :active.sync="isModalActive" :canCancel='false' has-modal-card>
        <login-modal></login-modal>
    </b-modal>
</template>

<script>
    import LoginModal from './login-modal.vue';
    export default {
        components: {
            LoginModal,
        },
        props: {
            timeout: {
                type: Number,
                default: '120',
                required: false,
            }
        },
        data() {
            return {
                isModalActive: false,
            }
        },
        mounted() {
            setTimeout(() => this.openLoginWindow(), this.timeout * 60000);
        },
        methods: {
            openLoginWindow() {
                this.isModalActive = true;
            },
        }
    }
</script>

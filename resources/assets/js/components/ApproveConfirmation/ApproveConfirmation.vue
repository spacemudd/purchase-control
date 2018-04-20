<template>
    <button class="button is-small is-warning"
            :class="{'is-loading': $isLoading('APPROVING_REQUEST')}"
            @click="confirm">
        {{ buttonText }}
    </button>
</template>

<script>
    export default {
        props: {
            buttonText: {
                required: false,
                type: String,
                default: 'Approve',
            },
            title: {
                required: false,
                type: String,
                default: 'Approve',
            },
            message: {
                required: false,
                type: String,
                default: 'Are you sure?',
            },
            /**
             * The action route.
             */
            action: {
                type: String,
                required: true,
            },
        },
        methods: {
            confirm() {
                this.$dialog.confirm({
                    title: this.title,
                    message: this.message,
                    onConfirm: () => this.sendRequest(),
                })
            },
            sendRequest() {
                this.$startLoading('APPROVING_REQUEST');
                axios.post(this.action).then(response => {
                    this.$endLoading('APPROVING_REQUEST');
                    this.$emit('success');
                }).catch(error => {
                    this.$endLoading('APPROVING_REQUEST');
                    alert(error.response.data.message);
                })
            },
        }
    }
</script>

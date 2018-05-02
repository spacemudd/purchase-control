<template>
    <button class="button is-danger is-small has-icon"
            :class="{'is-loading': isLoading}"
            @click="confirm">
        <span class="icon"><i class="fa fa-trash"></i></span>
        <span>{{ buttonText }}</span>
    </button>
</template>

<script>
    export default {
        props: {
            url: {
                type: String,
                required: true,
            },
            redirect: {
                type: String,
                required: false,
            },
            buttonText: {
                type: String,
                required: false,
                default: 'Delete',
            },
            confirmText: {
                type: String,
                required: false,
                default: 'Delete',
            },
        },
        data() {
            return {
                isLoading: false,
            }
        },
        methods: {
            confirm() {
                this.$dialog.confirm({
                    title: 'Delete',
                    message: 'Are you sure?',
                    type: 'is-danger',
                    confirmText: this.confirmText,
                    onConfirm: () => this.delete(),
                })
            },
            delete() {
                this.isLoading = true;
                axios.delete(this.url)
                    .then(response => {
                        this.$emit('deleted');

                        if(response.data.redirect) {
                            window.location.href = response.data.redirect;
                        }
                    })
            },
        }
    }
</script>

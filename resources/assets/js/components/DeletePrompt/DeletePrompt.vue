<template>
    <button class="button is-danger is-small has-icon"
            :class="{'is-loading': isLoading}"
            @click="confirm">
        <span class="icon"><i class="fa fa-trash"></i></span>
        <span>{{ $t('words.delete') }}</span>
    </button>
</template>

<script>
    export default {
        props: {
            id: {
                type: Number,
                required: true,
            },
            url: {
                type: String,
                required: true,
            },
            redirect: {
                type: String,
                required: false,
            }
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
                    confirmText: 'Delete Requisition',
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

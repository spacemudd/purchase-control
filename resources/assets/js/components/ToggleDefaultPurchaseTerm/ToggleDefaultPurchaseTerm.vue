<template>
    <div class="field" @click.prevent="toggle()">
        <div class="button is-outlined is-link is-small is-loading" v-if="isLoading"></div>
        <b-checkbox v-model="enabled" size="is-small" v-else>
            <slot></slot>
        </b-checkbox>
    </div>
</template>

<script>
    export default {
        props: {
            termId: {
                type: Number,
                required: true,
            },
            enabledProp: {
                type: Boolean,
                required: true,
            },
        },
        data() {
            return {
                enabled: false,
                isLoading: false,
            }
        },
        mounted() {
            this.enabled = this.enabledProp;
        },
        methods: {
            toggle() {
                if(!this.isLoading) {
                    if(this.enabled) {
                        this.disabled();
                    } else {
                        this.enable();
                    }
                }
            },
            enable() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/terms/enable', {
                    term_id: this.termId,
                }).then(() => {
                    this.isLoading = false;
                    this.enabled = true;
                    this.$toast.open({
                        duration: 500,
                        message: 'Term enabled',
                        type: 'is-success',
                    });
                }).catch(() => {
                    this.isLoading = false;
                    alert('Error occurred');
                })
            },
            disabled() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/terms/disable', {
                    term_id: this.termId,
                }).then(() => {
                    this.isLoading = false;
                    this.enabled = false;
                    this.$toast.open({
                        duration: 500,
                        message: 'Term disabled',
                        type: 'is-success',
                    });
                }).catch(() => {
                    alert('Error occurred');
                })
            }
        }
    }
</script>

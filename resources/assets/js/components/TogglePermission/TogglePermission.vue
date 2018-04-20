<template>
    <div class="field" @click.prevent="toggle()">
        <div class="button is-transparent is-link is-small is-loading" v-if="isLoading"></div>
        <b-checkbox v-model="enabled" v-else>
            <slot></slot>
        </b-checkbox>
    </div>
</template>

<script>
    export default {
        props: {
            roleId: {
                type: Number,
                required: true,
            },
            permissionName: {
                type: String,
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
                        this.detachPermission();
                    } else {
                        this.attachPermission();
                    }
                }
            },
            attachPermission() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/roles/attach-permission', {
                    role_id: this.roleId,
                    permission_name: this.permissionName,
                }).then(() => {
                    this.isLoading = false;
                    this.enabled = true;
                    this.$toast.open({
                        duration: 500,
                        message: this.getTrans('messages.permission-added'),
                        type: 'is-success',
                    });
                }).catch(() => {
                    this.isLoading = false;
                    alert('Error occurred');
                })
            },
            detachPermission() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/roles/detach-permission', {
                    role_id: this.roleId,
                    permission_name: this.permissionName,
                }).then(() => {
                    this.isLoading = false;
                    this.enabled = false;
                    this.$toast.open({
                        duration: 500,
                        message: this.getTrans('messages.permission-removed'),
                        type: 'is-success',
                    });
                }).catch(() => {
                    alert('Error occurred');
                })
            }
        }
    }
</script>

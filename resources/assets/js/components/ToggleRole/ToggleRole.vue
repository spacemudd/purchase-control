<template>
    <div class="field" @click.prevent="toggle()">
        <div class="button is-transparent is-link is-loading" v-if="isLoading"></div>
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
            userId: {
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
                        this.detachRole();
                    } else {
                        this.attachRole();
                    }
                }
            },
            attachRole() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/users/attach-role', {
                    role_id: this.roleId,
                    user_id: this.userId,
                }).then(() => {
                    this.isLoading = false;
                    this.enabled = true;
                    this.$toast.open({
                        duration: 500,
                        message: this.getTrans('messages.role-added'),
                        type: 'is-success',
                    });
                }).catch(() => {
                    this.isLoading = false;
                    alert('Error occurred');
                })
            },
            detachRole() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/users/detach-role', {
                    role_id: this.roleId,
                    user_id: this.userId,
                }).then(() => {
                    this.isLoading = false;
                    this.enabled = false;
                    this.$toast.open({
                        duration: 500,
                        message: this.getTrans('messages.role-removed'),
                        type: 'is-success',
                    });
                }).catch(() => {
                    alert('Error occurred');
                })
            }
        }
    }
</script>

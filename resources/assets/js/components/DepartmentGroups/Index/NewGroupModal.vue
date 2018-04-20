<template>
    <form action="">
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ $t('words.new-department-group') }}</p>
            </header>
            <section class="modal-card-body">
                <b-field :label="$t('words.code')">
                    <b-input
                            type="text"
                            v-model="code"
                            required>
                    </b-input>
                </b-field>

                <b-field :label="$t('words.description')">
                    <b-input type="text"
                             v-model="description"
                             required>
                    </b-input>
                </b-field>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-primary"
                        :class="{'is-loading': isLoading}"
                        @click.prevent="sendNewDepartmentGroupRequest">{{ $t('words.save') }}</button>
                <button class="button" type="button" @click.prevent="$parent.close()">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                code: '',
                description: '',
                isLoading: false,
            }
        },
        methods: {
            sendNewDepartmentGroupRequest() {
                this.isLoading = true;
                axios.post(this.apiUrl() + '/department-groups/store', {
                    code: this.code,
                    description: this.description
                }).then(response => {
                    this.isLoading = false;
                    this.$emit('createdNewDepartmentGroup');
                }).catch(error => {
                    alert(error.response.data.message)
                    this.isLoading = true;
                })
            }
        }

    }
</script>

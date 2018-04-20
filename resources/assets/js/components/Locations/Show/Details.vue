<template>
    <div>
        <div class="columns is-vcentered">
            <div class="column is-6">
                <h1 class="title is-5">{{ $t('words.details') }}</h1>
            </div>
            <div class="column is-6">
                <button class="button is-primary pull-right" @click="openNewDetailModal">
                    <span class="icon is-small">
                        <i class="fa fa-plus-square"></i>
                    </span>
                    <span>{{ $t('words.new-detail') }}</span>
                </button>

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                    <new-detail-modal @closedModal="closeNewDetailModal()" @updateDetails="updateDetails()" :location-id="location.id" v-if="showNewDetailModal"></new-detail-modal>
                </transition>
            </div>
        </div>


        <div class="columns">

            <div class="column is-12">

                <div v-if="isLoadingItems">
                    <loading-screen size="is-medium"></loading-screen>
                </div>
                <table class="table is-fullwidth is-size-7" v-else>
                    <thead>
                    <tr>
                        <th>{{ $t('words.name') }}</th>
                        <th>{{ $t('words.value') }}</th>
                        <th class="has-text-right">{{ $t('words.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody v-if="detailsItems">
                    <tr v-for="(detail, index) in detailsItems">
                        <td class="has-text-primary">{{ detail.name }}</td>
                        <td>{{ detail.value }}</td>
                        <td>
                            <button @click="deleteDetail(detail.id)"
                                    class="button is-pulled-right is-small is-danger">
                                        <span class="icon is-small">
                                            <i class="fa fa-ban"></i>
                                        </span>
                                <span>{{ $t('words.delete') }}</span>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>

        </div>


    </div>
</template>

<script>
    import NewDetailModal from './NewDetailModal.vue';

    export default {
        components: {
            NewDetailModal,
        },
        props: {
            location: {
                required: true,
            }
        },
        computed: {
            detailsItems: {
                get() {
                    return this.items;
                },
                set(value) {
                    this.items = value;
                }
            }
        },
        mounted() {
            this.detailsItems = this.location.details;
        },
        data() {
            return {
                isLoading: false,
                isLoadingItems: false,

                items: null,

                showNewDetailModal: false,
            }
        },
        methods: {
            openNewDetailModal() {
                this.showNewDetailModal = true;
            },
            closeNewDetailModal() {
                this.showNewDetailModal = false;
            },
            updateDetails() {
                this.isLoadingItems = true;
                axios.get(this.apiUrl() + '/locations/' + this.location.id).then(response => {
                    this.detailsItems = response.data.details;
                    this.isLoadingItems = false;
                })
            },
            deleteDetail(id) {

                this.isLoadingItems = true;

                axios.delete(this.apiUrl() + '/locations/details/delete', {
                    params: {
                        id: id,
                    }
                }).then(response => {

                    this.isLoadingItems = false;
                    this.updateDetails();

                }).catch(response => {

                    alert('Error occurred when deleting the detail record.');

                    this.isLoadingItems = false;
                })
            }
        }
    }
</script>

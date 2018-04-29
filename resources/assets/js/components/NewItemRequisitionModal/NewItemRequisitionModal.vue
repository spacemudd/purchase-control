<template>
    <form @submit.prevent="save">
        <div class="modal-card" style="width: auto;min-height:600px;">
            <header class="modal-card-head">
                <p class="modal-card-title">Add new item</p>
            </header>
            <section class="modal-card-body">

                <!-- Form Errors -->
                <div class="notification is-danger" v-if="form.errors.length > 0">
                    <strong>An error occurred.</strong>
                    <br>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <div class="field">
                    <label class="label">Item <span class="has-text-danger">*</span></label>

                    <div class="control">
                        <b-input v-if="form.itemTemplate" :value="form.itemTemplate.code + ' - ' + form.itemTemplate.name" @click="form.itemTemplate=null">
                        </b-input>
                        <select-item-template v-else
                                              :url="this.apiUrl() + '/search/item-templates'"
                                              @select="selectedItemTemplate"
                        >
                        </select-item-template>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Quantity <span class="has-text-danger">*</span></label>
                    <div class="control">
                        <b-input type="number" step="1" v-model="form.qty"></b-input>
                    </div>
                </div>

                <transition enter-active-class="animated fadeInLeft" leave-active-class="animated fadeOutLeft">
                    <div v-if="form.itemTemplate" class="notification">
                        <p class="title is-spaced">{{ form.itemTemplate.name }} <span class="has-text-grey">x {{ form.qty }}</span></p>
                        <p class="subtitle">{{ form.itemTemplate.model_number }}</p>
                        <button type="button" class="button is-small is-text" @click="form.itemTemplate=null">Clear</button>
                    </div>
                </transition>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button class="button is-primary">Add</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            requisitionId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {

                form: {
                    itemTemplate: null,
                    qty: 1,
                    errors: [],
                },
            }
        },
        mounted() {
            //
        },
        methods: {
            selectedItemTemplate(itemTemplate) {
                this.form.itemTemplate = itemTemplate;
            },
            save() {
                this.form.errors = [];

                axios.post(this.apiUrl() + '/purchase-requisition-items', {
                    purchase_requisition_id: this.requisitionId,
                    qty: this.form.qty,
                    item_template_id: this.form.itemTemplate.id,
                })
                    .then(response => {
                        this.form.name = '';
                        this.form.scopes = [];
                        this.form.errors = [];

                        this.$emit('saved');

                        this.$parent.close();
                    })
                    .catch(response => {
                        if (typeof response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },
        }
    }
</script>

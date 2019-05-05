<template>
    <form @submit.prevent="sendForm">
        <div class="modal-card" style="width: auto;height:500px">
            <header class="modal-card-head">
                <p class="modal-card-title">New Item Catalog</p>
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

                <div class="columns is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Description <span class="has-text-danger">*</span></label>
                            <div class="control">
                                <b-input name="description" v-model="description" required></b-input>
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Code <b-tooltip label="A unique identifier"><span style="opacity:0.25" class="icon"><i class="fa fa-question"></i></span></b-tooltip></label>
                            <div class="control">
                                <b-input name="code" v-model="code" placeholder="Auto-generated if blank"></b-input>
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Category</label>
                            <div class="control">
                                <!--<div class="select is-fullwidth">-->
                                <!--<select v-model="mainCategorySelected">-->
                                <!--<option :value="null"></option>-->
                                <!--<option v-for="category in categories"-->
                                <!--:value="category">{{ category.name }}</option>-->
                                <!--</select>-->
                                <!--</div>-->
                                <b-select v-model="selectedCategory" expanded>
                                    <optgroup v-for="mainCategory in categories" :label="mainCategory.name">
                                        <option v-for="category in mainCategory.children" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </optgroup>
                                </b-select>
                            </div>
                        </div>
                    </div>

                    <!--
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Sub Category</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="category_id" v-model="selectedCategory">
                                        <option></option>
                                        <template v-if="mainCategorySelected">
                                            <option v-for="category in mainCategorySelected.children" :value="category.id">{{ category.name }}</option>
                                        </template>
                                    </select>
                                </div>
                                <slot></slot>
                            </div>
                        </div>
                    </div>
                    -->

                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Manufacturer</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select v-model="manufacturerId">
                                        <option :value="null"></option>
                                        <option v-for="manufacturer in manufacturers"
                                                :value="manufacturer.id">{{ manufacturer.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label for="unit_price" class="label">Default Price</label>
                            <p class="control">
                                <input id="unit_price" type="number"
                                       step="0.01"
                                       min="0"
                                       class="input"
                                       name="unit_price" v-model="unitPrice">
                            <p class="help">Auto-fill when creating PO items. Can be overruled.</p>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Model Details</label>

                            <div class="control">
                                <b-input type="textarea"
                                         maxlength="191"
                                         v-model="modelDetails">
                                </b-input>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Cancel</button>
                <button type="submit" class="button is-primary" :class="{'is-loading': $isLoading('SAVING_ITEM_CATALOG')}">{{ $t('words.save') }}</button>
            </footer>
        </div>
    </form>
</template>

<script>
  export default {
    props: {
      action: {
        type: String,
      },
      categories: {
        type: Array,
        default: [],
      },
      manufacturers: {
        default: [],
      },
    },
    data() {
      return {
        form: {
          errors: []
        },

        mainCategorySelected: null,

        description: null,
        code: null,
        unitPrice: null,
        modelDetails: '',
        selectedCategory: null,
        manufacturerId: null,
      }
    },
    mounted() {
      //
    },
    methods: {
      sendForm() {
        this.$startLoading('SAVING_ITEM_CATALOG');
        axios.post(this.action, {
          description: this.description,
          code: this.code,
          unit_price: this.unitPrice,
          model_details: this.modelDetails,
          category_id: this.selectedCategory,
          manufacturer_id: this.manufacturerId,
        }).then(response => {
          let code = response.data.data.code
          this.$toast.open({
            type: 'is-success',
            message: code + ' - new item catalog was created.',
          });
          this.showCreateModal = false;
          this.$endLoading('SAVING_ITEM_CATALOG');
        }).catch(error => {
          if (typeof error.response.data === 'object') {
            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
          } else {
            this.form.errors = ['Something went wrong. Please try again.'];
          }
          this.$endLoading('SAVING_ITEM_CATALOG');
        });
      },
    },
  }
</script>

<style lang="sass">
    //
</style>

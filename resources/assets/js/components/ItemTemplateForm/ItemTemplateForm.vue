<template>
    <form @submit.prevent="sendForm">
        <input type="hidden" name="csrf_token" :value="csrfToken">
        <div class="columns is-multiline">
            <div class="column is-12">
                <!-- Form Errors -->
                <div class="notification is-danger" v-if="form.errors.length > 0">
                    <p class="mb-0">Something went wrong.</p>
                    <br>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>
            </div>

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
                    <label class="label">Code <span class="has-text-danger">*</span> <b-tooltip label="A unique identifier"><span style="opacity:0.25" class="icon"><i class="fa fa-question"></i></span></b-tooltip></label>
                    <div class="control">
                        <b-input name="code" v-model="code" required></b-input>
                        <p class="help">Recommended: {3 letters code}-{3 letters category}-{number}</p>
                    </div>
                </div>
            </div>

            <div class="column is-6">
                <div class="field">
                    <label class="label">Category</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select v-model="mainCategorySelected">
                                <option :value="null"></option>
                                <option v-for="category in categories"
                                        :value="category">{{ category.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

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
                    <p class="help">Auto-fills when creating PO items</p>
                </div>
            </div>

            <div class="column is-12">
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

            <div class="column is-12 has-text-right">
                <a class="button is-text" :href="cancelUrl">{{ $t('words.cancel') }}</a>
                <button type="submit" class="button is-primary" :class="{'is-loading': $isLoading('SENDING_FORM')}">{{ $t('words.save') }}</button>
            </div>
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
      csrfToken: {
        type: String,
      },
      cancelUrl: {
        type: String,
      }
    },
    watch: {
      description(newDescription, oldDescription) {
        this.code = newDescription.substring(0, 3).toUpperCase()
          .replace(/[^\w ]+/g,'')
          .replace(/ +/g,'-');
      },
      selectedCategory(newCategoryId, oldCategoryId) {
        let categorySlug = '';

        let category = this.mainCategorySelected.children.filter(category => {
          return category.id === newCategoryId
        })

        if(category[0]) {
          let categoryName = category[0].name;

          categorySlug = '-' + categoryName.substring(0, 3)
            .toUpperCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-');
        }

        let descriptionSlug = this.description.substring(0, 3).toUpperCase()
          .replace(/[^\w ]+/g,'')
          .replace(/ +/g,'-');

       this.code = descriptionSlug + categorySlug;

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
        this.$startLoading('SENDING_FORM');

        axios.post(this.action, {
          description: this.description,
          code: this.code,
          unit_price: this.unitPrice,
          model_details: this.modelDetails,
          category_id: this.selectedCategory,
          manufacturer_id: this.manufacturerId,
        }).then(response => {
          let url = response.data.redirect;
          window.location = url
          }).catch(error => {
            if (typeof error.response.data === 'object') {
              this.form.errors = _.flatten(_.toArray(error.response.data.errors));
            } else {
              this.form.errors = ['Something went wrong. Please try again.'];
            }
            this.$endLoading('SENDING_FORM');
          });
      },
    }
  }
</script>

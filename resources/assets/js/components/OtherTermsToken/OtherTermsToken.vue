<template>
    <div>
        <div :class="{'transparent-highlighter-bg': canEdit}" @click="editToken">
            <span v-if="!form.otherTerms"><i>[{{ placeholder }}]</i></span>
            <span v-else><pre :class="{'transparent-highlighter-bg': canEdit}">{{ form.otherTerms }}</pre></span>
        </div>
        <div v-if="is_editing" style="margin-top:20px">
            <div class="field">
                <b-input v-model="form.otherTerms" maxlength="1000" type="textarea"></b-input>
            </div>
            <div class="field">
                <div class="control has-text-right">
                    <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
                    <button class="button is-small is-primary"
                            :class="{'is-loading': $isLoading('SAVING_OTHER_TERMS')}"
                            @click="saveToken">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: {
      /**
       * Purchase order number.
       */
      id: {
        type: Number,
        required: true,
      },
      /**
       * An HTTP PUT method URL to save the token.
       */
      url: {
        type: String,
        required: true,
      },
      /**
       * The name of the token/field/column-name of the resource.
       */
      name: {
        type: String,
        required: true,
      },
      /**
       * The value to display to the user.
       */
      value: {
        type: String,
        required: false,
      },
      placeholder: {
        type: String,
        default: 'TOKEN',
      },
      canEdit: {
        type: Boolean,
        default: false,
      },
    },
    data() {
      return {
        is_editing: false,

        old_value: this.value,

        form: {
          otherTerms: '',
          errors: [],
        },
      }
    },
    mounted() {
      this.form.otherTerms = this.value;
    },
    methods: {
      rollback() {
        this.form.otherTerms = this.value;
        this.is_editing = false;
      },
      editToken() {
        if(this.canEdit) {
          this.is_editing = true;
        }
      },
      saveToken(address) {
        if(this.canEdit) {
          this.$startLoading('SAVING_OTHER_TERMS');
          axios.put(this.apiUrl() + '/purchase-orders/' + this.id + '/tokens', {
            'name': this.name,
            'value': this.form.otherTerms,
          })
            .then(response => {
              this.$toast.open({
                type: 'is-success',
                message: 'Saved',
              });

              this.form.otherTerms = response.data.other_terms;
              this.is_editing = false;

              this.$endLoading('SAVING_OTHER_TERMS');
            })
            .catch(error => {
              alert(error.response.data);
              this.$endLoading('SAVING_OTHER_TERMS');
            })
        }
      },
    }
  }
</script>

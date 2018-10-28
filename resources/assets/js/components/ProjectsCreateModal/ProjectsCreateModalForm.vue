<template>
    <form @submit.prevent="save()">
        <div class="modal-card">
            <div class="modal-card-head">
                <p class="modal-card-title">
                    New Project
                </p>
            </div>
            <section class="modal-card-body">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">{{ $t('words.location') }} <span class="has-text-danger">*</span></label>
                            <div class="control">
                                <input type="text" class="input" v-model="location" rquired>
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Cost Center <span class="has-text-danger">*</span></label>
                            <div class="control">
                                <input v-if="costCenter" type="text" class="input" :value="departmentName" readonly>
                                <simple-search
                                        v-else
                                        :size="'is-small'"
                                        search-endpoint="departments"
                                        @selectedRecord="updateDepartment"
                                ></simple-search>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">{{ $t('words.cancel') }}</button>
                <button :class="{'is-loading': $isLoading('SAVING_PROJECT')}"
                        class="button is-success"
                        type="submit"
                >{{ $t('words.save') }}
                </button>
            </footer>
        </div>
    </form>
</template>

<script>
  export default {
    data() {
      return {
        location: '',
        selectedCostCenter: '',
        costCenter: '',
      }
    },
    computed: {
      departmentName() {
        if(this.department) {
          return this.department.code + ' - ' + this.department.description;
        } else {
          return '';
        }
      }
    },
    methods: {
      updateDepartment(payload) {
        this.selectedCostCenter = payload;
      },
      save() {
        this.$startLoading('SAVING_PROJECT');

        axios.post(this.apiUrl() + '/projects', {
          location: this.location,
          cost_center_id: this.selectedCostCenter.id,
        }).then(response => {
          this.$toast.open({
            type: 'is-success',
            message: 'Success - ' + this.location,
          });
          this.$endLoading('SAVING_PROJECT');
          this.$parent.close();
        }).catch(error => {
          alert(error.response.data.message);
          this.$endLoading('SAVING_PROJECT');
        });
      },
    }
  }
</script>

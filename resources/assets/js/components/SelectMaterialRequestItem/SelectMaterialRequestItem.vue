<template>
    <div class="select is-fullwidth">
        <select ref="newItemDescription" class="select" v-model="selected" @change="selectItem(selected)">
            <option value=""></option>
            <optgroup v-if="request.items.length != 0"
                      v-for="request in data"
                      :label="request.number">
                <option v-for="item in request.items"
                        :value="item.id">
                    {{ item.description }}
                </option>
            </optgroup>
        </select>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        data: [],
        selected: null,
        isFetching: false
      }
    },
    mounted() {
      this.getData();
    },
    methods: {
      selectItem(item) {
        this.$emit('input', item);
      },
      getData() {
        this.data = [];
        this.isFetching = false;

        let vm = this;

        axios.get(this.apiUrl() + '/material-requests/approved-items')
          .then(response => {
            this.data = response.data;
            vm.$refs.newItemDescription.focus();
          })
          .catch(error => {
            this.data = [];
            throw error;
          })
          .finally(() => {
            this.isFetching = false;
          })
      },
    }
  }
</script>

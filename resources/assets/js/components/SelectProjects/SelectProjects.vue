<template>
    <span>
        <input type="hidden" v-if="selectedProject" :name="name" :value="selectedProject.id">

        <!-- Selected item -->
        <input v-if="selectedProject"
               type="text"
               class="input"
               :value="selectedProject.location + ' - Cost Center: ' + selectedProject.cost_center.code"
               @click="selectedProject=search=null"
               readonly>

        <!-- When searching -->
        <b-autocomplete
                v-else
                v-model="search"
                :data="projects"
                :loading="isLoading"
                field="code"
                @input="getData"
                @select="selectProject">
            <template slot="empty" v-if="!isLoading">No results found</template>
            <template slot-scope="props">
                <a class="dropdown-item">
                    {{ props.option.location }} - Cost Center: {{ props.option.cost_center.code }}
                </a>
            </template>
        </b-autocomplete>
    </span>
</template>

<script>
  import debounce from "lodash/debounce";

  export default {
    props: {
      /**
       * api endpoint to search for projects.
       */
      url: {
        type: String,
        required: true,
      },
      name: {
        type: String,
        required: false,
      }
    },
    data() {
      return {
        isLoading: false,
        selectedProject: null,
        search: null,
        projects: [],
      }
    },
    mounted() {

    },
    methods: {
      getData: debounce(function () {
        this.projects = []
        this.isLoading = true
        axios.get(this.url + '?q=' + this.search)
          .then(response => {
            response.data.data.forEach((item) => this.projects.push(item))
            this.isLoading = false
          })
          .catch((error) => {
            this.isLoading = false
            throw error
          })
      }, 500),
      selectProject(project) {
        this.selectedProject = project;
        this.$emit('selected', project);
      }
    }
  }
</script>

<template>
    <!--<b-modal :active="isActive" @update:active="value => isActive = value">-->
    <b-modal :active.sync="showCreateModal">
        <item-catalog-create-modal-form :action="action"
                                 :categories="categories"
                                 :manufacturers="manufacturers"
        >
        </item-catalog-create-modal-form>
    </b-modal>
</template>

<script>
    import ItemCatalogCreateModalForm from './ItemCatalogCreateModalForm'
    export default {
      component: {
        ItemCatalogCreateModalForm,
      },
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
      computed: {
        showCreateModal: {
          get() {
            return this.$store.getters['itemCatalog/showCreateModal'];
          },
          set(value) {
            this.$store.commit('itemCatalog/showCreateModal', value);
          }
        },
      },
      watch: {
        description(newDescription, oldDescription) {
          // this.code = newDescription.substring(0, 3).toUpperCase()
          //   .replace(/[^\w ]+/g, '')
          //   .replace(/ +/g, '-');
        },
        selectedCategory(newCategoryId, oldCategoryId) {
          // let categorySlug = '';
          //
          // let category = this.mainCategorySelected.children.filter(category => {
          //   return category.id === newCategoryId
          // })
          //
          // if (category[0]) {
          //   let categoryName = category[0].name;
          //
          //   categorySlug = '-' + categoryName.substring(0, 3)
          //     .toUpperCase()
          //     .replace(/[^\w ]+/g, '')
          //     .replace(/ +/g, '-');
          // }
          //
          // let descriptionSlug = this.description.substring(0, 3).toUpperCase()
          //   .replace(/[^\w ]+/g, '')
          //   .replace(/ +/g, '-');
          //
          // this.code = descriptionSlug + categorySlug;

        },
      },
    }
</script>

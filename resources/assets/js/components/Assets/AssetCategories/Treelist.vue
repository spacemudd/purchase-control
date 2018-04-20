<template>
	<div>
		asd
		<div class="treeview hover">
			<ul v-for="category in categories">

			</ul>
		</div>
</div>

	</div>
</template>

<script>
	import * as treeviewUtil from '../../../utils/treeviewlist.js'

	export default {
		mounted() {
			this.getCategories()
		},
		data() {
			return {
				isLoading: false,
				categories: null,
				selectedCategory: null
			}
		},
		methods: {
			getCategories() {
				axios.get(this.apiUrl() + '/assets/category-list').then(response => {
					this.categories = this.sortCategories(response.data)
					this.isLoading = false
					
				})
				// .catch(error => {
				// 	console.log('Error occured while fetching asset categories list')
				// })
			},
			selectCategory(category) {
				this.selectCategory = category
			},
			sortCategories(list) {
				return treeviewUtil.generate(list)
			}
		}
	}
</script>

<style lang="less" scoped>

@defaultColor: rgba(255, 255, 255, 0.5);
@hoverColor: rgba(255, 255, 255, 0.9);

.transition-duration(@duration: 1s) {
  -webkit-transition-duration: @duration;
  -moz-transition-duration: @duration;
  -ms-transition-duration: @duration;
  -o-transition-duration: @duration;
  transition-duration: @duration;
}

.transition-property(@property) {
  -webkit-transition-property: @property;
  -moz-transition-property: @property;
  -ms-transition-property: @property;
  -o-transition-property: @property;
  transition-property: @property;
}

.user-select(@select: none) {
  -webkit-user-select: @select;
  -moz-user-select: @select;
  -ms-user-select: @select;
  -o-user-select: @select;
  user-select: @select;
}

h2 {
  font-family: 'Handlee', cursive;
  font-weight: normal;
  color: @hoverColor;
  margin-left: 1em;
  margin-bottom: 0;
}

.treeview {
  float: left;
  .user-select;

  &:hover input ~ label:before,
  &.hover input ~ label:before{
	opacity: 1.0;
	.transition-duration(0.5s);
  }
  
  ul {
	.transition-duration;
	list-style: none;
	padding-left: 1em;
	
	li {
	  span{
		.transition-property(color);
		.transition-duration;
	  
		&:hover {
		  color: @hoverColor;
		  .transition-duration(0.3s);
		}
	  }
	}
  }
  
  input{
	display: none;
	
	&:checked ~ ul {
	  display: none;
	}
	
	& ~ label {
	  cursor: pointer;
	}
	
	& ~ label:before {
	  content: '';
	  width: 0;
	  height: 0;
	  position: absolute;
	  margin-left: -1em;
	  margin-top: 0.4em;
	  border-width: 4px;
	  border-style: solid;
	  border-top-color: transparent;
	  border-right-color: @defaultColor;
	  border-bottom-color: @defaultColor;
	  border-left-color: transparent;
	  opacity: 0.0;
	  .transition-property(opacity);
	  .transition-duration;
	}

	&:checked ~ label:before {
	  margin-left: -0.8em;
	  border-width: 5px;
	  border-top-color: transparent;
	  border-right-color: transparent;
	  border-bottom-color: transparent;
	  border-left-color: @defaultColor;
	}
  }
}

</style>

/*
*	
*/
export function generate(root_nodes) {

	var categories = [];

	for(let [key, root_node] of Object.entries(root_nodes)) {
		
		console.log('Starting to generate for root_node... ')
		console.log(root_node)
		categories.push( this.generateFromTopNode(root_node) )

	}

	console.log('Final categories:')
	console.log(categories)
	return categories;
}

export function generateFromTopNode(results) {

	var sorting = results[0]; // Top node

	console.log('Array shift:')
	results = this.array_shift(results)
	console.log(results)

	console.log('Sorting:')
	console.log( sorting )

	if( sorting['left'] + 1 == sorting['right'] ) {
		sorting['leaf'] = true;
	} else {
		for(let [key, result] of results.entries()) {
			console.log("First in 'For' (key, result)")
			console.log(key)
			console.log(result)

			if(result['left'] > sorting['right']) // not a child
				break;
			if(rgt > result['left']) // not a top-level child
				continue;

			sorting['children'] = this.generate(this.array_values(results));

			for(let [child_key, child] of results.entries()) {
				console.log('Child key:')
				console.log(child_key)
				console.log('Child')
				console.log(child)

				if(child['right'] < result['right']) {
					results.splice(child_key)
				}
			}

			var rgt = result['right']
			results.splice(key)
		}
	}

	delete sorting.left
	delete sorting.right

	console.log(sorting)
}


export function array_shift (input) { // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/array_shift/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Martijn Wieringa
  //      note 1: Currently does not handle objects
  //   example 1: array_shift(['Kevin', 'van', 'Zonneveld'])
  //   returns 1: 'Kevin'
  // var _checkToUpIndices = function (arr, ct, key) {
  //   // Deal with situation, e.g., if encounter index 4 and try
  //   // to set it to 0, but 0 exists later in loop (need to
  //   // increment all subsequent (skipping current key, since
  //   // we need its value below) until find unused)
  //   if (arr[ct] !== undefined) {
  //     var tmp = ct
  //     ct += 1
  //     if (ct === key) {
  //       ct += 1
  //     }
  //     ct = _checkToUpIndices(arr, ct, key)
  //     arr[ct] = arr[tmp]
  //     delete arr[tmp]
  //   }
  //   return ct
  // }
  // if (inputArr.length === 0) {
  //   return null
  // }
  // if (inputArr.length > 0) {
  //   return inputArr.shift()
  // }
  // 
  

  for(let [key, value] of Object.entries(input)) {
  	tmpArr[tmpArr.length] = value
  }

  return tmpArr;

}

export function array_values (input) { // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/array_values/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Brett Zamir (http://brett-zamir.me)
  //   example 1: array_values( {firstname: 'Kevin', surname: 'van Zonneveld'} )
  //   returns 1: [ 'Kevin', 'van Zonneveld' ]
  var tmpArr = []
  var key = ''
  for (key in input) {
    tmpArr[tmpArr.length] = input[key]
  }
  return tmpArr
}

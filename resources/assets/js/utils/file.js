/*
*	Blob is retrieved using an axios call and downloaded to the client side
*	as if they were doing Right Click -> Save As.
*	appendChild() and removeChild() are used because without them, downloading via FF doesn't work.
*
*   @param blob			Blob object (e.g. response.data from axios' callback)
*   @param filename		Filename of the file (taken from the content-headers of the call back or assigned at runtime)
*/
export function downloadBlob(blob, filename) {

	let link = document.createElement('a');
	link.href = window.URL.createObjectURL(blob);
	link.download = filename;

	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);

}

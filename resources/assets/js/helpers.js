
const apiVersion = '1';

// if(process.env.NODE_ENV === 'production') {
    var envUrl = window.location.origin + '/pd';
// } else {
//     var envUrl = window.location.origin;
// }

export default {
    baseUrl() {
        return envUrl;
    },
    apiUrl() {
        return envUrl + "/api/v" + apiVersion;
    },
    scannerUrl() {
        return 'https://localhost:8087';
    },
}

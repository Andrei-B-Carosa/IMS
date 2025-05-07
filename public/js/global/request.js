export class RequestHandler {
    constructor() {}

    get(url) {
        return axios.get(url)
            .then(response => response.data)
            .catch(error => {
                console.error(error);
                throw error;
            });
    }
    post(url, data, multipart = false) {
        data.append("_token", this.getToken());
        let config = false;
        if (multipart) {
            config = { headers: { 'Content-Type': 'multipart/form-data' } };
        }

        return axios.post(url, Object.fromEntries(data.entries()), config)
            .then(response => response.data)
            .catch(error => {
                console.error(error);
                throw error;
            });
    }

    getToken() {
        return $('meta[name="csrf-token"]').attr("content");
    }
}


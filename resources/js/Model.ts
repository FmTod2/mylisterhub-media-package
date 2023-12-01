import { type HTTPRequestConfig, Model as BaseModel } from 'vue-api-query';

export default class Model extends BaseModel {
    static $baseURL: string

    // Define a base url for a REST API
    baseURL(): string {
        return Model.$baseURL;
    }

    // Implement a default request method
    request(config: HTTPRequestConfig) {
        return this.$http.request(config)
    }
}

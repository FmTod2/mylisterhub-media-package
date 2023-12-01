import { type HTTPRequestConfig, Model } from 'vue-api-query';

export default class MediaModel extends Model {
    static $baseURL: string

    // Define a base url for a REST API
    baseURL(): string {
        return MediaModel.$baseURL;
    }

    // Implement a default request method
    request(config: HTTPRequestConfig) {
        return this.$http.request(config)
    }
}

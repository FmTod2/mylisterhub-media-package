import { type HTTPRequestConfig, Model } from 'vue-api-query';
export default class MediaModel extends Model {
    static $baseURL: string;
    baseURL(): string;
    request(config: HTTPRequestConfig): any;
}

import axios from 'axios'
import MediaModel from './model'
import Image from "./models/Image";
import Video from "./models/Video";

MediaModel.$http = axios

export default {
    Image,
    Video,
    MediaModel,
}

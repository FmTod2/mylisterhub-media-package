import r from "axios";
import { Model as a } from "vue-api-query";
class e extends a {
  static $baseURL;
  // Define a base url for a REST API
  baseURL() {
    return e.$baseURL;
  }
  // Implement a default request method
  request(t) {
    return this.$http.request(t);
  }
}
class o extends e {
  resource() {
    return "images";
  }
}
class i extends e {
  resource() {
    return "videos";
  }
}
e.$http = r;
const c = {
  Image: o,
  Video: i,
  MediaModel: e
};
export {
  c as default
};

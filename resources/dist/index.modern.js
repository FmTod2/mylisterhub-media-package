import e from"axios";import{Model as r}from"vue-api-query";class s extends r{baseURL(){return s.$baseURL}request(e){return this.$http.request(e)}}s.$baseURL=void 0,s.$http=e;var t={Image:class extends s{resource(){return"images"}},Video:class extends s{resource(){return"videos"}},MediaModel:s};export{t as default};
//# sourceMappingURL=index.modern.js.map

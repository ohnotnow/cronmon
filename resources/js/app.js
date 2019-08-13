window.Vue = require("vue");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component("job-list", require("./components/JobList.vue").default);
Vue.component("job-tabs", require("./components/JobTabs.vue").default);
Vue.component("api-key-toggle", require("./components/ApiKeyToggle.vue").default);
const app = new Vue({
  el: "#app"
});


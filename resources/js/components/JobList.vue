<template><div>

<div class="mb-2 text-right">
    <input placeholder="Filter..." v-model="filter" class="w-full md:w-1/4 shadow appearance-none border focus:border-grey-dark rounded py-2 px-3 text-grey-darker leading-tight" type="text" name="login" required autofocus>
</div>

<div class="md:flex md:flex-row hidden justify-between p-4 hover:bg-grey-lightest font-semibold border-b-2 border-orange">
    <div class="flex-1 flex-col md:flex-row">
        <div class="flex flex-col md:flex-row">
            <div class="flex-initial text-center text-orange-darker pr-4 w-8">
            </div>
            <div class="flex-1 ">
                Name
            </div>
            <div class="flex-1 text-orange-darker">Schedule</div>
            <div class="flex-1 text-orange-darker">Last Run</div>
            <div v-if="admin" class="flex-1 text-orange-darker">
                Owner
            </div>
            <div v-if="admin" class="flex-1 text-orange-darker">
                Team
            </div>
        </div>
    </div>
    <div v-if="!admin" class="flex-1 align-left text-orange-darker">
        URI
    </div>
</div>

<div
    v-for="job in filteredJobs"
    :key="job.id"
    class="flex flex-col md:flex-row justify-between p-4 mb-4 md:mb-0 shadow md:shadow-none hover:bg-grey-lightest"
>
    <div class="flex-1 flex-col md:flex-row">
        <div class="flex flex-col md:flex-row">
            <div class="flex-initial text-left md:text-center mb-2 md:mb-0 text-orange-darker pr-4">
                <span v-if="job.is_awol && job.is_silenced" title="Awol - silenced">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M13 16v5a1 1 0 0 1-1 1H9l-3-6a2 2 0 0 1-2-2 2 2 0 0 1-2-2v-2c0-1.1.9-2 2-2 0-1.1.9-2 2-2h7.59l4-4H20a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2.41l-4-4H13zm0-2h1.41l4 4H20V4h-1.59l-4 4H13v6zm-2 0V8H6v2H4v2h2v2h5zm0 2H8.24l2 4H11v-4z"/></svg>
                </span>
                <span v-if="job.is_awol && !job.is_silenced" title="Awol - alerting">
                    <svg class="animated infinite flip" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M13 16v5a1 1 0 0 1-1 1H9l-3-6a2 2 0 0 1-2-2 2 2 0 0 1-2-2v-2c0-1.1.9-2 2-2 0-1.1.9-2 2-2h7.59l4-4H20a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2.41l-4-4H13zm0-2h1.41l4 4H20V4h-1.59l-4 4H13v6zm-2 0V8H6v2H4v2h2v2h5zm0 2H8.24l2 4H11v-4z"/></svg>
                </span>
                <span v-if="! job.is_awol">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M12.76 3.76a6 6 0 0 1 8.48 8.48l-8.53 8.54a1 1 0 0 1-1.42 0l-8.53-8.54a6 6 0 0 1 8.48-8.48l.76.75.76-.75zm7.07 7.07a4 4 0 1 0-5.66-5.66l-1.46 1.47a1 1 0 0 1-1.42 0L9.83 5.17a4 4 0 1 0-5.66 5.66L12 18.66l7.83-7.83z"/></svg>
                </span>
            </div>
            <div class="flex-1 ">
                <a :href="job.show_url" class="text-orange hover:text-orange-dark">
                    {{ job.name }}
                </a>
            </div>
            <div class="flex-1 text-orange-darker">{{ job.schedule }}</div>
            <div class="flex-1 text-orange-darker" :title="job.last_run">{{ job.last_run_diff }}</div>
            <div v-if="admin"  class="flex-1 text-orange-darker">
                <a class="text-orange" :href="job.user_url">
                    {{ job.username }}
                </a>
            </div>
            <div v-if="admin"  class="flex-1 text-orange-darker">
                {{ job.teamname }}
            </div>
        </div>
    </div>
    <div v-if="!admin" class="flex-1 align-left text-orange-darker">
        {{ job.uri }}
    </div>
</div>

</div></template>

<script>
export default {
  props: ["jobs", "admin"],
  data() {
    return {
      filter: ""
    };
  },
  computed: {
    filteredJobs() {
      return this.jobs.filter(job => job.name.includes(this.filter));
    }
  }
};
</script>

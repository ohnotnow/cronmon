<template><div>
    <div class="tabs flex justify-between mb-2">
      <ul class="list-reset flex">
        <li class="mr-1" :class="classList('userTab')">
            <a @click.prevent="currentTab='userTab'" id="yourjobs" href="#" class="text-xl inline-block py-2 px-4 text-orange-dark hover:text-orange-darker font-normal">
                Your jobs
            </a>
        </li>
        <li id="teamjobstab" :class="classList('teamTab')">
            <a @click.prevent="currentTab='teamTab'" id="teamjobs" href="#" class="text-xl inline-block py-2 px-4 text-orange-dark hover:text-orange-darker font-normal">
                Team jobs
            </a>
        </li>
       </ul>
    </div>
    <div v-if="currentTab == 'userTab'">
        <job-list :jobs='userjobs'></job-list>
    </div>
    <div v-if="currentTab == 'teamTab'" >
        <job-list :jobs='teamjobs'></job-list>
    </div>

</div></template>

<script>
export default {
  props: ["userjobs", "teamjobs"],
  data() {
    return {
      currentTab: "userTab"
    };
  },
  computed: {},
  methods: {
    classList(tabName) {
      if (tabName === this.currentTab) {
        return "border-b-2 border-orange-light";
      }
      return "border-b-2 border-transparent hover:border-orange";
    }
  }
};
</script>

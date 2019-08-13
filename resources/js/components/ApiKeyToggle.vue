<template>
	<div>
		<span v-if="visible" v-text="apikey"></span>
		<button @click.prevent="visible = ! visible" class="button text-base" v-text="visible ? 'Hide' : 'Show'">Toggle Visibility</button>
	</div>
</template>

<script>
export default {
  props: ["apikey"],
  data() {
    return {
    	visible: false,
    };
  },
 }

</script>
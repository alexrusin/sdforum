<template>
	
	<button type="button" :class="classes" @click="subscribe">Subscribe</button>

</template>

<script>

	export default {
		props: ['active'],

		computed: {
			classes() {
				return ['btn', this.activeBtn ? 'btn-primary' : 'btn-default'];
			}
		},

		data() {
			return {
				activeBtn: this.active
			}
		},
		
		methods: {
			subscribe() {
				axios[(this.activeBtn ? 'delete' : 'post')]
				(location.pathname + '/subscriptions');
				this.activeBtn = ! this.activeBtn;
			}
			
		}
	}

</script>
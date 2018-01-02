<script>

	import Replies from '../components/RepliesComponent.vue';
	export default {
		components: {Replies},
		props: ['initialRepliesCount'],
		data() {
			return {
				repliesCount: this.initialRepliesCount
			};
		}
	}
</script>
<script>

	import Replies from '../components/RepliesComponent.vue';
	import SubscribeButton from '../components/SubscribeButton.vue';
	export default {
		components: {Replies, SubscribeButton},
		props: ['thread'],
		data() {
			return {
				repliesCount: this.thread.replies_count,
				locked: this.thread.locked,
				editing: false,
				title: this.thread.title,
				body: this.thread.body
			};
		},

		methods: {
			toggleLock() {
				axios[this.locked ? 'delete' : 'post']('/locked-threads/' + this.thread.slug);
				this.locked = !this.locked;
			},

			cancel() {
				this.thread.title = this.title;
				this.thread.body = this.body;
				this.editing = false;
			}, 

			update() {
				axios.patch('/threads/' + this.thread.channel.slug + '/' + this.thread.slug, {
					title: this.thread.title, 
					body: this.thread.body
				}).then(()=>{
					this.title = this.thread.title;
					this.body = this.thread.body;
					this.editing = false;
					flash('Your thread has been updated');
				});
			}
		}
	}
</script>
<template>
	<div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
	    <div class="panel-heading">
			<div class="level">
				<h5 class="flex">
					<a :href="'/profiles/' + data.owner.name" v-text="data.owner.name">
						</a> said <span v-text="ago"></span>
				</h5>
				
				<div v-if="signedIn">
					<favorite :reply="data"></favorite>
				</div>
				
			</div>
	       
	    </div>
	    
	    <div class="panel-body">
	    	<div v-if="editing">
	    		<form @submit.prevent="update">
		    		<div class="form-group">
		    			<wysiwyg v-model="body"></wysiwyg>
		    		</div>
		    		<button type="submit" class="btn-xs btn-success">Update</button>
		    		<button type="button" class="btn-xs btn-link" @click="editing=false">Cancel</button>
	    		</form>
	    	</div>
	    	<div ref="body" v-else v-html="body">
	       		
	    	</div>
	    </div>
		
		<div class="panel-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">

			<div v-if="authorize('owns', reply)">
				<button type="button" class="btn btn-xs mr-1 btn-primary" @click="editing=true">Edit</button>

			<button type="button" class="btn btn-danger mr-1 btn-xs" @click="destroy">Delete</button>
			</div>
			
			<button type="button" class="btn btn-default ml-a btn-xs" @click="markBestReply" v-show="!isBest" v-if="authorize('owns', reply.thread)">Best Reply?</button>

			
		</div>
		
	</div>
</template>

<script>
	import Favorite from './FavoriteComponent.vue';
	import moment from 'moment';
	export default {
		props: ['data'],
		components: {Favorite},
		data() {
			return {
				editing: false,
				id: this.data.id,
				body: this.data.body,
				isBest: this.data.isBest,
				reply: this.data
			};
		},

		computed: {

			ago() {
				return moment(this.data.created_at + 'Z').fromNow();
			}
		},

		created() {
			window.events.$on('best-reply-selected', id => {
				this.isBest = (id === this.id);
			});
		},

		mounted() {
            this.highlight(this.$refs['body']);
        },
         watch: {
            editing() {
                if(this.editing) return;
            	this.$nextTick(() => {
            		this.highlight(this.$refs['body']);
            	});  
            }
        },

		methods: {
			update() {
				axios.patch('/replies/' + this.id, {
					body: this.body
				})
				.then(response => {
					flash('Updated!');
					this.editing = false;
				})
				.catch(error => flash(error.response.data, 'danger'));

				
			},

			destroy() {
				axios.delete('/replies/' + this.id);

				this.$emit('deleted', this.data.id);
			},

			markBestReply() {

				this.isBest = true;

				axios.post('/replies/' + this.reply.id + '/best');

				window.events.$emit('best-reply-selected', this.reply.id);
			}

		}
	}

</script>
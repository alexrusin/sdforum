<template>
	<div :id="'reply-'+id" class="panel panel-default">
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
		    			<textarea class="form-control" v-model="body" required></textarea>
		    		</div>
		    		<button type="submit" class="btn-xs btn-success">Update</button>
		    		<button type="button" class="btn-xs btn-link" @click="editing=false">Cancel</button>
	    		</form>
	    	</div>
	    	<div v-else v-html="body">
	       		
	    	</div>
	    </div>
		
		<div class="panel-footer level" v-if="canUpdate">
			<button type="button" class="btn btn-xs mr-1 btn-primary" @click="editing=true">Edit</button>
			

			<button type="button" class="btn btn-danger btn-xs" @click="destroy">Delete</button>
			
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
				body: this.data.body
			};
		},

		computed: {
			signedIn() {
				return window.App.signedIn;
			},

			canUpdate() {
				return this.authorize(user => this.data.user_id == user.id);
			},

			ago() {
				return moment(this.data.created_at + 'Z').fromNow();
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
			}


		}
	}

</script>
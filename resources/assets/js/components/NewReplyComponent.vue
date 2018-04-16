<template>
	<div>
		<div v-if="signedIn">
			<div class="form-group">
                <textarea name="body" id="body" class="form-control" placeholder="Have something to say?" rows="5" v-model="body" required></textarea>
            </div>
            <button type="button" class="btn btn-default" @click="addReply">Post</button>
		</div>
		
        <p class="text-center" v-else>Please <a href="/login">sign in</a> to participate</p>
   		
   
	</div>

</template>

<script>
	import 'jquery.caret';
	import 'at.js';
	export default {
		data() {
			return {
				body: ''
			};
		},

		mounted() {
			$('#body').atwho({
				at: "@",
				delay: 750,
				callbacks: {
					remoteFilter: function(query, callback) {
						axios.get('/api/users', {
							params: {
								name: query
							}
						})
						.then(response => {
							callback(response.data);
						});
					}
				}

			});
		},

		methods: {
			addReply() {
				axios.post(location.pathname + '/replies', {body: this.body})
					.then(({data}) =>{
						this.body = '';

						flash('Your reply has been posted');

						this.$emit('created', data);
					})
					.catch(error => flash(error.response.data, 'danger'));
			}
		}
	}
</script>
<template>
	<div>
		<div class="level">
			<img :src="avatar" width="50" height="50" class="mr-1"> 
			<h1>
				{{user.name}}
				<small v-text="reputation"></small>
			</h1>
			
		</div>
		
		<form v-if="canUpdate" method="POST" enctype="multipart/form-data">
			<image-upload name="avatar" @loaded="onLoad"></image-upload>	
		</form>
		
	</div>
</template>

<script>
	import ImageUpload from './ImageUpload.vue';

	export default {
		props: ['user'],
		components: {ImageUpload},
		data() {
			return {
				avatar: this.user.avatar_path
			};
		},

		computed: {
			canUpdate() {
				return this.authorize(user => user.id === this.user.id);
			},

			reputation() {
				return this.user.reputation + 'XP';
			}
		},

		methods: {
			onLoad(avatar) {
				this.avatar = avatar.src;
				this.persist(avatar.file);
			},

			persist(file) {
				let data = new FormData();
				data.append('avatar', file);
				axios.post('/api/users/${this.user.name}/avatar', data)
					.then(response => flash(response.data));
			}
		}
	}
</script>
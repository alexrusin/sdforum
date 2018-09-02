
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.events = new Vue();

window.flash = function(message, level = 'success') {
	window.events.$emit('flash', {message, level});
};

let authorizations = require('./authorizations');
Vue.prototype.authorize = function (...params) {
	
	if(! window.App.signedIn) return false;

	if(typeof params[0] === 'string') {

		return authorizations[params[0]](params[1]);
	}

	return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;

/**
 * We'll load highlight.js library which allows us to easily enable syntax 
 * highlighting within <pre><code> blocks. It also allows highlighting 
 * within custom html blocks with a wide variety of color schemes. 
 */
let Highlighter = require('highlight.js');

require('highlight.js/styles/foundation.css'); // load Foundation style

Vue.prototype.highlight = function (block) {
	if( !block) return;

	block.querySelectorAll('pre').forEach(node =>
	    Highlighter.highlightBlock(node)
	    );
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/FlashComponent.vue'));
Vue.component('paginator', require('./components/PaginatorComponent.vue'));
Vue.component('user-notifications', require('./components/UserNotifications.vue'));
Vue.component('avatar-form', require('./components/AvatarForm.vue'));
Vue.component('wysiwyg', require('./components/Wysiwyg.vue'));
Vue.component('thread-view', require('./pages/ThreadComponent.vue'));


const app = new Vue({
    el: '#app'
});

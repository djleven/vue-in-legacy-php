import Vue from 'vue';
import VueResource from 'vue-resource';
import HelloWorld from '../components/HelloWorld.vue';

Vue.use(VueResource);

Vue.component('hello-world', HelloWorld);

export function hello(element) {
	console.log('hello');
	return new Vue({
		el: element,
		render: h => h(HelloWorld)
	});
}

export function goodbye(element) {
	console.log('bye');
	return new Vue({
		el: element,
		render: h => h(HelloWorld)
	});
}

// or for one method only
// export default helloWorld

<template>
	<div id="app">
		<calendario @move="onMove" @create="onCreate" @extend="onExtend"/>
	</div>
</template>

<script>
import Calendario from './components/calendario.vue'

const axios = require('axios').default;

export default {
	name: 'app',
	components: {
		Calendario
	},
	mounted(){

		let data = {
			mes: 4,
			año: 2020
		}

		// console.log('test');

		// axios.post('http://127.0.0.1:8000/api/calendario/reservaciones', data)
		// .then(res => {

		// 	console.log(res);
		// });
	},
	methods: {

		onMove(dragingObject) {

			console.log(dragingObject);
		},
		onCreate(newCard) {

			console.log(newCard);
		},
		onExtend(extendingObject) {

			console.log(extendingObject);
		}
	}
}
</script>

<style>

</style>

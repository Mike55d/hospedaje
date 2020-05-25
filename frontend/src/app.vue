<template>
	<div id="app">
		<calendario @move="onMove" @create="onCreate" @extend="onExtend" @remove="onRemove" @edit="onEdit"/>
	</div>
</template>

<script>
import Calendario from './components/calendario.vue'
import storeFunctions from './components/storeFunctions';

const axios = require('axios').default;

export default {
	name: 'app',
	components: {
		Calendario
  },
  mixins: [
    storeFunctions
  ],
  data: function() {

    return {

    }
  },
	mounted(){

		let data = {
			mes: 4,
			año: 2020
    }

    // this.openModal();
	},
	methods: {

    onEdit(card){

      console.log(card);
    },
    onRemove(card){

      axios.post('http://127.0.0.1:8000/calendario/delReserva', {reserva: card.id})
      .then(res => {

        console.log(res);
      });
      console.log(card);
    },
		onMove(dragingObject) {

			console.log(dragingObject);
		},
		onCreate(newCard) {

			console.log(newCard);
		},
		onExtend(extendingObject) {

			console.log(extendingObject);
    },
	}
}
</script>

<style>

</style>

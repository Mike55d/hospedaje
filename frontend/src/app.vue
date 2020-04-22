<template>
	<div id="app">
		<calendario @move="onMove" @create="onCreate" @extend="onExtend"/>
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

		onMove(dragingObject) {

			console.log(dragingObject);
		},
		onCreate(newCard) {

      // this.card = newCard;
      // this.initialDate = this.card.initDate;
      // this.endDate = this.card.endDate;
      // this.openModal();
			// console.log(newCard);
		},
		onExtend(extendingObject) {

			console.log(extendingObject);
    },
	}
}
</script>

<style>

</style>

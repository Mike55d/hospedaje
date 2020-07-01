<template>
	<div id="app">
		<calendario @move="onMove" @create="onCreate" @extend="onExtend" @remove="onRemove" @edit="onEdit"/>
	</div>
</template>

<script>
import Calendario from './components/calendario.vue'
import storeFunctions from './components/storeFunctions';
import {rutaApi} from './components/rutas'


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
      console.log('root edit', card)
      axios.post(rutaApi+'api/calendario/editReserva', {
        reservacion: card.id,
        cama: this.items[card.roomIndex].beds[card.bedIndex].id,
        persona: 1,
        fechaInicio: card.initDate,
        fechaFin: card.endDate,
        status:card.status
      })
      .then(res => {
        console.log(res);
      });
    },
    onRemove(card){

      axios.post(rutaApi+'api/calendario/delReserva', {reserva: card.id})
      .then(res => {

        console.log(res);
      });
      console.log(card);
    },
		onMove(dragingObject) {

      axios.post(rutaApi+'api/calendario/editReserva', {
        reservacion: dragingObject.id,
        cama: this.items[dragingObject.roomIndex].beds[dragingObject.bedIndex].id,
        persona: 1,
        fechaInicio: dragingObject.initDate,
        fechaFin: dragingObject.endDate
      })
      .then(res => {

        console.log(res);
      });
		},
		onCreate(newCard) {
      console.log('newCard',newCard);
			axios.post(rutaApi+'api/calendario/newReserva', {
        cama: this.items[newCard.roomIndex].beds[newCard.bedIndex].id,
        persona: newCard.persona.id,
        reserva: newCard.reserva.id,
        fechaInicio: newCard.initDate,
        fechaFin: newCard.endDate
      }).then(res => {

        console.log(res);
      });
		},
		onExtend(extendingObject) {

      console.log(extendingObject);
      axios.post(rutaApi+'api/calendario/editReserva', {
        reservacion: extendingObject.id,
        cama: this.items[extendingObject.roomIndex].beds[extendingObject.bedIndex].id,
        persona: 1,
        fechaInicio: extendingObject.initDate,
        fechaFin: extendingObject.endDate
      })
      .then(res => {

        console.log('edit');
        console.log(res);
      });
    },
	}
}
</script>

<style>

</style>

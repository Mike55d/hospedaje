<template>

<div>
  <div class="modal fade bs-example-modal-lg" id="my-modal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" id="close-modal" data-dismiss="modal" style="display: none">x</button>
          <button type="button" class="close" @click="cancel" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
        </div>
        <div class="modal-body">
          <div class="row modal-row">
            <div class="col-md-3 col-sm-3">
              <h2>Grupos</h2>
              <ul class="filters">
                <li v-bind:class="[grupo.id == grupoS.id ? 'active':'']" class="hover" @click="getSolicitudes(grupo)" v-for="(grupo, index) of grupos" :key="'grupo-' + index">{{ grupo.nombre }}</li>
              </ul>
            </div>
            <div class="col-md-3 col-sm-3">
              <h2>Solicitudes</h2>
              <ul class="solicitudes">
                <li v-bind:class="[solicitud.id == solicitudS.id ? 'active':'']" class="hover" @click="getPersonas(solicitud)" v-for="(solicitud, index) of solicitudesAprovadas" :key="'solicitud-' + index"> id: {{ solicitud.id }} fecha: {{ solicitud.fecha |formatDate}}</li>
              </ul>
            </div>
            <div class="col-md-3 col-sm-3">
              <h2>Personas</h2>
              <ul class="users">
                <li v-bind:class="[persona.id == personaS.id ? 'active':'']" class="hover" @click="selectPersona(persona)" v-for="(persona, index) of personas" :key="'persona-' + index">{{ persona.id }} {{persona.tratamiento}}. {{ persona.nombre }}</li>
              </ul>
            </div>
            <div class="col-md-3 col-sm-3">
              <h2>User Data</h2>
              <ul v-if="personaS" class="user-data">
                <li>ID: {{personaS.id}}</li>
                <li>TRATAMIENTO: {{personaS.tratamiento}}</li>
                <li>NOMBRE: {{personaS.nombre}}</li>
                <li>USER: {{personaS.user.username}}</li>
                <li>GRUPO: {{personaS.grupo.nombre}}</li>
              </ul>
              <h2>Dates</h2>
              <div class="form-group">
                <label>Initial date</label>
                <input class="form-control" v-model="initialDate" type="date">
              </div>
              <div class="form-group">
                <label>Final date</label>
                <input class="form-control" v-model="endDate" type="date">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" @click="cancel">Cancel</button>
          <button class="btn btn-default" @click="editCard">Accept</button>
          <button class="btn btn-danger" @click="remove">Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <button id="open-modal" :style="{display: 'none'}" data-toggle="modal" data-backdrop="static"
    data-target=".bs-example-modal-lg">test</button>
</div>
</template>

<script>

import storeFunctions from './storeFunctions';
import moment from 'moment';
const axios = require('axios').default;

export default {

  mixins: [
    storeFunctions
  ],
  props: ['show', 'card'],
  data: function(){

    return {

      grupos: [
        {id: 1, nombre:'Groups'},
        {id: 2, nombre: 'Users'}
      ],
      personas: [],
      localCard: null,
      initialDate: null,
      endDate: null,
      user: null,
      grupoS:0,
      solicitudS:0,
      solicitudesAprovadas:[],
      personaS:0,
      persona:0,
    }
  },
  mounted(){

    axios.get('http://localhost:8000/api/calendario/grupos')
      .then(res => {

        this.grupos = res.data;
      });
  },
  methods: {

    cancel(){

      console.log('thiscard', this.card);
      if(this.card.user == null){

        console.log('test');
        this.clearSpace();
        this.$emit('cancel', this.card);
      }

      this.closeModal();
    },
    

    clearSpace(){

      let monthFirstDate = new Date(this.date.year, this.date.month, 1);
      let monthLastDate = new Date(this.date.year, this.date.month + 1, 0);
      let initDateSplit = this.card.initDate.split('-');
      let initDate = new Date(initDateSplit[0], initDateSplit[1] - 1, initDateSplit[2]);
      let endDateSplit = this.card.endDate.split('-');
      let endDate = new Date(endDateSplit[0], endDateSplit[1] - 1, endDateSplit[2]);
      let initMonthDay = initDate.getTime() < monthFirstDate.getTime() ? monthFirstDate.getDate() : initDate.getDate();
      let endMonthDay = endDate.getTime() > monthLastDate.getTime() ? monthLastDate.getDate() : endDate.getDate();
      let bed = this.items[this.card.roomIndex].beds[this.card.bedIndex];
      for(let i = initMonthDay -1; (i < endMonthDay); i++){

        bed.days[i].busy = null;
      }
    },
    getSolicitudes(grupo){
      this.grupoS = grupo;
      axios.get(`http://localhost:8000/api/calendario/solicitudesAprobadas`, 
      {params:{grupo: grupo.id}}
      )
        .then(res => {
          console.log(res);
          // this.grupos = res.data;
          this.solicitudesAprovadas = res.data;
        }).catch(e => {
          console.log(e);
        });
    },
    getPersonas(solicitud){
      this.solicitudS = solicitud;
      axios.get(`http://localhost:8000/api/calendario/personas`,
      {params:{solicitud: solicitud.id}}
      )
        .then(res => {
          console.log(res);
          this.personas = res.data;
        }).catch(e => {

          console.log(e);
        });
    },
    selectPersona(persona){
      console.log(persona);
      this.personaS = persona;
    },
    remove(){

      this.clearSpace();
      if(this.card.user == null){

        this.$emit('cancel', this.card);
      }else{

        this.$emit('remove', this.card);
      }

      this.closeModal();
    },
    openModal(){

			let btnModal = document.getElementById('open-modal');
			btnModal.click();
		},
		closeModal(){

			let btnModal = document.getElementById('close-modal');
			btnModal.click();
    },
    editCard(){
      this.localCard.persona = this.personaS;
      this.localCard.reserva = this.solicitudS;
      if(this.card.initDate != this.initialDate || this.card.endDate != this.endDate){

        const MS_OF_THE_DAY = (1000 * 3600 * 24);
        let initDateSplit = this.initialDate.split('-');
        let initDate = new Date(initDateSplit[0], initDateSplit[1] - 1, initDateSplit[2]);
        let endDateSplit = this.endDate.split('-');
        let endDate = new Date(endDateSplit[0], endDateSplit[1] - 1, endDateSplit[2]);

        if(initDate.getTime() <= endDate.getTime()){

          let lengthCard = ((endDate.getTime() - initDate.getTime()) / MS_OF_THE_DAY) + 1;
          let monthFirstDate = new Date(this.date.year, this.date.month, 1);
          let monthLastDate = new Date(this.date.year, this.date.month + 1, 0);
          let initDay;
          let bed = this.items[this.card.roomIndex].beds[this.card.bedIndex];
          let initMonthDay = initDate.getTime() < monthFirstDate.getTime() ? monthFirstDate.getDate() : initDate.getDate();
          let endMonthDay = endDate.getTime() > monthLastDate.getTime() ? monthLastDate.getDate() : endDate.getDate();

          let lastInitDateSplit = this.card.initDate.split('-');
          let lastInitDate = new Date(lastInitDateSplit[0], lastInitDateSplit[1] - 1, lastInitDateSplit[2]);
          let lastEndDateSplit = this.card.endDate.split('-');
          let lastEndtDate = new Date(lastEndDateSplit[0], lastEndDateSplit[1] - 1, lastEndDateSplit[2]);
          let lastInitMonthDay = lastInitDate.getTime() < monthFirstDate.getTime() ? monthFirstDate.getDate() : lastInitDate.getDate();
          let lastEndMonthDay = lastEndtDate.getTime() < monthLastDate.getTime() ? monthLastDate.getDate() : lastEndtDate.getDate();

          if(initDate.getTime() >= monthFirstDate.getTime() && initDate.getTime() <= monthLastDate.getTime()){

            initDay = bed.days[initDate.getDate() - 1];
            this.localCard.lengthCard = lengthCard;
            this.localCard.width = this.cardDimentions.width * lengthCard;
            this.localCard.left = initDay.left;
            this.localCard.initDay = initDay;
            this.localCard.initDate = `${initDate.getFullYear()}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(initDate.getMonth() + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(initDate.getDate())}`;
            this.localCard.endDate = `${endDate.getFullYear()}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(endDate.getMonth() + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(endDate.getDate())}`;
          }

          if(initDate.getTime() < monthFirstDate.getTime() && endDate.getTime() >= monthFirstDate.getTime()){

            initDay = bed.days[0];
            let daysLeft = ((monthFirstDate.getTime() - initDate.getTime()) / MS_OF_THE_DAY);
            let margin = ((daysLeft * this.cardDimentions.width) + 10) + 'px';
            this.localCard.lengthCard = lengthCard;
            this.localCard.width = this.cardDimentions.width * lengthCard;
            this.localCard.left = initDay.left - (daysLeft * this.cardDimentions.width);
            this.localCard.infoMargin = margin;
            this.localCard.initDay = initDay;
            this.localCard.initDate = `${initDate.getFullYear()}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(initDate.getMonth() + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(initDate.getDate())}`;
            this.localCard.endDate = `${endDate.getFullYear()}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(endDate.getMonth() + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(endDate.getDate())}`;
          }

          let busy = false;

          for(let i = initMonthDay -1; (i < endMonthDay); i++){

            if(bed.days[i].busy != null && bed.days[i].busy != this.card.id){

              busy = true;
            }
          }

          if(!busy){

            for(let i = lastInitMonthDay -1; (i < lastEndMonthDay); i++){

              bed.days[i].busy = null;
            }

            for(let i = initMonthDay -1; (i < endMonthDay); i++){

              if(bed.days[i].busy == null){

                bed.days[i].busy = this.localCard.id;
              }
            }
            this.$emit('edit-card', this.card, this.localCard);
            this.setDestiny(null);
            this.closeModal();
          }
        }
      }else{
        if(this.card.user == null){
          this.localCard.user = 1;
          // axios.post('http://localhost:8000/calendario/newReserva', {
          //   cama: this.items[this.localCard.roomIndex].beds[this.localCard.bedIndex].id,
          //   persona: 1,
          //   fechaInicio: this.localCard.initDate,
          //   fechaFin: this.localCard.endDate
          // }).then(res => {

          //   console.log(res);
          // });
          this.$emit('edit-card', this.card, this.localCard);
          this.closeModal();
        }
      }
    }
  },
  watch: {

    show(){

      this.localCard = {...this.card};
      this.initialDate = this.card.initDate;
      this.endDate = this.card.endDate;
      this.openModal();
    }
  },
  filters:{
    formatDate: function(value){
      if (value) {
        return moment(String(value)).format('MM/DD/YYYY')
      }
    }
  }

}
</script>
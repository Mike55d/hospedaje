<template>

<div>
  <div class="modal fade bs-example-modal-lg" id="my-modal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div v-bind:class="[(card && card.persona) ? 'modal-md':'modal-size']" class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" id="close-modal" data-dismiss="modal" style="display: none">x</button>
          <button type="button" class="close" @click="cancel" aria-hidden="true">×</button>
          <h4 v-if="card && !card.persona" class="modal-title text-center" id="myLargeModalLabel">Solicitudes Aprobadas</h4>
          <h4 v-else class="modal-title text-center" id="myLargeModalLabel">Editar Reservacion</h4>
        </div>
        <div class="modal-body">
          <div  class="row modal-row">
            <div v-if="card && !card.persona" class="col-md-3 col-sm-3">
              <div  class="well well-sm card-modal">
                  <h2 class="font-bold text-center">Grupos</h2>
                  <ul class="filters font-bold">
                    <li v-bind:class="[grupo.id == grupoS.id ? 'selected':'']" class="hover" @click="getSolicitudes(grupo)" v-for="(grupo, index) of grupos" :key="'grupo-' + index">{{ grupo.nombre }}</li>
                  </ul>
              </div>
            </div>
            <div v-if="card && !card.persona" class="col-md-3 col-sm-3">
              <div  class="well well-sm card-modal">
              <h2 class="text-center font-bold">Solicitudes</h2>
                <h3 v-if="solicitudesAprovadas && solicitudesAprovadas.length == 0 ">No hay solicitudes</h3>
                <ul v-if="solicitudesAprovadas && solicitudesAprovadas.length" class="solicitudes font-bold">
                  <li  v-bind:class="[solicitud.id == solicitudS.id ? 'selected':'']" class="hover" @click="getPersonas(solicitud)" v-for="(solicitud, index) of solicitudesAprovadas" :key="'solicitud-' + index"> id: {{ solicitud.id }} fecha: {{ solicitud.fecha |formatDate}}</li>
                </ul>
              </div>
            </div>
            <div v-if="card && !card.persona" class="col-md-3 col-sm-3">
              <div  class="well well-sm card-modal">
              <h2 class="text-center font-bold">Personas</h2>
              <h3 v-if="personas && personas.length == 0 ">No hay personas por asignar</h3>
              <ul v-if="personas && personas.length" class="users font-bold">
                <li v-bind:class="[persona.id == personaS.id ? 'selected':'']" class="hover" @click="selectPersona(persona)" v-for="(persona, index) of personas" :key="'persona-' + index">{{ persona.id }} {{persona.tratamiento}}. {{ persona.nombre }}</li>
              </ul>
              </div>
            </div>
            <div v-bind:class="[(card && card.persona) ? 'col-md-12':'col-md-3 ']">
              <div class="well well-sm card-datos">
              <h2 class="font-bold">Datos</h2>
              <ul v-if="personaS" class="user-data">
                <li class="font-bold">ID: {{personaS.id}}</li>
                <li class="font-bold">TRATAMIENTO: {{personaS.tratamiento}}</li>
                <li class="font-bold">NOMBRE: {{personaS.nombre}}</li>
                <li class="font-bold">USER: {{personaS.user.username}}</li>
                <li class="font-bold">GRUPO: {{personaS.grupo.nombre}}</li>
              </ul>
              <ul v-else-if="card && card.persona" class="user-data">
                <li class="font-bold">ID: {{card.persona.id}}</li>
                <li class="font-bold">TRATAMIENTO: {{card.persona.tratamiento}}</li>
                <li class="font-bold">NOMBRE: {{card.persona.nombre}}</li>
                <li class="font-bold">USER: {{card.persona.user.username}}</li>
                <li class="font-bold">GRUPO: {{card.persona.grupo.nombre}}</li>
              </ul>
              </div>
              <div class="well well-sm card-datos">
              <h2 class="font-bold">Fechas</h2>
              <div class="form-group">
                <label>Facha de llegada</label>
                <input class="form-control" v-model="initialDate" type="date">
              </div>
              <div class="form-group">
                <label>Fecha de salida</label>
                <input class="form-control" v-model="endDate" type="date">
              </div>
              </div>
            </div>
            <div v-if="card && card.persona" class="well weel-sm col-md-12">
              <select @change="onChangeStatus" v-model="card.status" class="form-control" name="" id="">
                <option value="pendiente">Pendiente</option>
                <option value="hospedado">Hospedado</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div v-bind:class="[(card && card.persona) ? 'col-md-12':'col-md-4 col-md-offset-8']" class="row">
            <div class="col-md-2">
              <button class="btn btn-danger btn-sm center-block mt-5" @click="remove">Borrar</button>
            </div>
            <div class="col-md-3">
              <button class="btn btn-info btn-sm center-block mt-5" @click="cancel">Cancelar</button>
            </div>
            <div class="col-md-6">
              <button v-bind:class="[(card && card.persona)?'btn-warning':'btn-success']" class="btn  btn-block center-block " @click="editCard">
                <span v-if="card && !card.persona">Crear</span>
                <span v-else>Editar</span>
              </button>
            </div>
          </div>
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
import $ from 'jquery'
import '../../js/jquery.blockUI';
import {rutaApi} from './rutas';


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
      personas:null,
      localCard: null,
      initialDate: null,
      endDate: null,
      user: null,
      grupoS:0,
      solicitudS:0,
      solicitudesAprovadas:null,
      personaS:0,
      oldStatus:null,
      statusChange:false,
    }
  },
  mounted(){
    moment.locale('es'); // 'en'
    console.log(moment().month(0).format('MMMM'))
    axios.get(rutaApi+'api/calendario/grupos')
      .then(res => {

        this.grupos = res.data;
      });
  },
  methods: {

    cancel(){
      console.log('thiscard', this.card);
      this.card.status = this.oldStatus;
      if(this.card.user == null){

        console.log('test');
        this.clearSpace();
        this.$emit('cancel', this.card);
      }

      this.closeModal();
    },
    showLoader(){
      $('.modal-size').block({
        message:'Cargando',
        css:{
          border: 'none',
          padding: '15px'
        }
      })
    },
    onChangeStatus(){
      this.statusChange = true;
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
      this.showLoader();
      this.personas = null;
      this.grupoS = grupo;
      axios.get(rutaApi+`api/calendario/solicitudesAprobadas`, 
      {params:{grupo: grupo.id}}
      )
        .then(res => {
          $('.modal-size').unblock();
          console.log(res);
          // this.grupos = res.data;
          this.solicitudesAprovadas = res.data;
        }).catch(e => {
          $('.modal-size').unblock();
          console.log(e);
        });
    },
    getPersonas(solicitud){
      this.showLoader();
      this.solicitudS = solicitud;
      axios.get(rutaApi+`api/calendario/personas`,
      {params:{solicitud: solicitud.id}}
      )
        .then(res => {
          $('.modal-size').unblock();
          console.log(res);
          this.personas = res.data;
        }).catch(e => {
          $('.modal-size').unblock();
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
    clearModal(){
    this.personas= 0;
    this.personaS=0;
    this.solicitudS= 0;
    this.solicitudesAprovadas= 0;
    this.grupoS= 0;
    this.statusChange = false;
    },
    openModal(){
			let btnModal = document.getElementById('open-modal');
			btnModal.click();
		},
		closeModal(){
			let btnModal = document.getElementById('close-modal');
      btnModal.click();
      this.clearModal();
    },
    editCard(){
      this.localCard.persona = this.personaS;
      this.localCard.reserva = this.solicitudS;
      if(this.card.initDate != this.initialDate || this.card.endDate != this.endDate || this.statusChange ){

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
      this.oldStatus = (this.card && this.card.status) ? this.card.status : null;
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
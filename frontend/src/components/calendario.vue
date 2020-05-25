<template>
<div class="calendario">

  <div class="controls-container">
		<controls></controls>
	</div>
  <div class="table-wrapper row">
    <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3" style="padding-right: 0">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Rooms</th>
            <th>Beds</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(room, roomIndex) of items">
            <tr @mouseleave="itemRowMouseOut" class="item-row" v-for="(bed, bedIndex) of room.beds" :key="'bed-' + bedIndex + '-' + roomIndex ">
              <td v-if="bedIndex == 0" :rowspan="room.beds.length">{{ room.number }}</td>
              <td :style="{'white-space': 'nowrap'}">{{ bed.name }}</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <div class="col-md-9 col-sm-9 col-xs-9 col-lg-9" style="padding-left: 0; position: static">
      <div @mousemove="mouseMove" @scroll="onScroll" id="calendar-container">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th v-for="day of date.monthDays" :key="'day-' + day" style="min-width: 60px">{{ day }}</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(room, roomIndex) of items">
              <tr @mouseleave="itemRowMouseOut" class="item-row" v-for="(bed, bedIndex) of room.beds" :key="'bed-' + bedIndex + '-' + roomIndex ">
                <td class="noselect day" v-for="(day, dayIndex) of bed.days" :key="'item-day-' + dayIndex"
                  @mousedown="dayMouseDown($event, day)"
                  @mouseenter="dayMouseEnter($event, day)"
                  :style="{
                    background: day.background,
                    height: '51px'
                  }">{{ day.busy }}</td>
              </tr>
            </template>
          </tbody>
        </table>
        <card
          v-for="(card, index) of cards"
          :card="card"
          :key="'card-' + index"
          @edit="edit(card)"></card>
      </div>
    </div>
  </div>

  <card-modal @edit-card="onEditCard" @remove="remove" :show="showCardModal" :card="editCard"></card-modal>

  <button @click="scrollLeft">Left</button>
  <button @click="scrollRight">Right</button>
</div>
</template>

<script>

import storeFunctions from './storeFunctions';
import mouseEventListeners from './mouseEventListeners';
import $ from 'jquery';
const axios = require('axios').default;

export default {
	mixins: [
		storeFunctions,
		mouseEventListeners
	],
	components: {
		'card': require('./card.vue').default,
    'controls': require('./controls.vue').default,
    'card-modal': require('./card-modal.vue').default
	},
	data: function(){

		return {
			dragStart: false,
			lengthCard: 0,
			startCard: null,
			endCard: null,
			startDay: null,
			endDay: null,
			daysExtend: 0,
			cards: [],
			isMounted: false,
      scrollDays: 0,
      scrollLimit: 0,
      showCardModal: false,
      editCard: null
		}
	},
	beforeMount(){

		let date = new Date();
		let year = date.getFullYear();
		let month = date.getMonth();
		let monthDays = new Date(year, month +1, 0).getDate();

		this.setDate({
			month,
			monthDays,
			year
		});

		this.setItemDays();
	},
	mounted(){

    this.setContainer(document.getElementById('calendar-container'));
    this.container.scroll(0, 0);
    this.scrollLimit = this.container.scrollWidth - this.container.clientWidth;

		this.setScrollPos({
			x: this.container.scrollLeft,
			y: this.container.scrollTop
		});
		document.body.addEventListener('mousedown', (e) => {

			this.setMouseDown(true);
		});

		document.body.addEventListener('mouseup', (e) => {

			this.setMouseDown(false);
		});

		document.addEventListener('mouseleave', (e) => {

			this.setDragingObject(null);
    });

    let data = {
      mes: this.date.month + 1,
      año: this.date.year
    }

    let cards = [];

    axios.post('http://127.0.0.1:8000/api/calendario/reservaciones', data)
		.then(res => {

      console.log(res);
      let items = [];
      res.data.forEach((item, indexItem) => {

        let beds = [];

        item.camas.forEach((bedReserv, indexBedREserv) => {

          beds.push({
            id: bedReserv.cama.id,
            name:bedReserv.cama.numero,
            days: [],
            reservations: bedReserv.reservaciones
          });
        });

        items.push({
          id: item.id,
          number: item.cuarto.numero,
          beds
        });
      });

      this.setItems(items);
      this.setItemDays();
      this.initItemDays();
      this.drawCards();
		});

		this.isMounted = true;

	},
	methods: {

    remove(card){

      let index = this.cards.indexOf(card);
      this.$emit('remove', card);
      this.cards.splice(index, 1);
    },
    edit(card){

      this.editCard = card;
      this.showCardModal = !this.showCardModal;
    },
    onEditCard(oldCard, newCard){

      let index = this.cards.indexOf(oldCard);
      this.cards.splice(index, 1, newCard);
      this.$emit('edit', newCard);
    },
    scrollRight(){

      let scroll = this.scrollDays + 300 > this.scrollLimit ? this.scrollLimit : this.scrollDays + 300;
      this.scrollDays = scroll;
      this.container.scroll({
        left: scroll,
        behavior: 'smooth'
      });
    },
    scrollLeft(){

      let scroll = this.scrollDays - 300 < 0 ? 0 : this.scrollDays - 300;
      this.scrollDays = scroll;
      this.container.scroll({
        left: scroll,
        behavior: 'smooth'
      });
    },
    drawCards(){

      let cards = [];
      const MS_OF_THE_DAY = (1000 * 3600 * 24);

      this.items.forEach((room, roomIndex) => {

        room.beds.forEach((bed, bedIndex) => {

          bed.reservations.forEach((reserv, reservIndex) => {

            let initDateSplit = reserv.fechaInicio.substring(0, 10).split('-');
            let initDate = new Date(initDateSplit[0], initDateSplit[1] -1, initDateSplit[2]);
            let endDateSplit = reserv.fechaFin.substring(0, 10).split('-');
            let endDate = new Date(endDateSplit[0], endDateSplit[1] -1, endDateSplit[2]);
            let lengthCard = ((endDate.getTime() - initDate.getTime()) / MS_OF_THE_DAY) + 1;
            let monthFirstDate = new Date(this.date.year, this.date.month, 1);
            let monthLastDate = new Date(this.date.year, this.date.month + 1, 0);
            let initDay = initDate.getTime() >= monthFirstDate.getTime() ? bed.days[initDate.getDate() -1] : bed.days[monthFirstDate.getDate() -1];
            let initMonthDay = initDate.getTime() < monthFirstDate.getTime() ? monthFirstDate.getDate() : initDate.getDate();
            let endMonthDay = endDate.getTime() > monthLastDate.getTime() ? monthLastDate.getDate() : endDate.getDate();
            let left = initDay.left;

            cards.push({
              id: reserv.id,
              name: 'Card x' + this.cards.length,
              lengthCard,
              background: 'blue',
              width: this.cardDimentions.width * lengthCard,
              left,
              top: bed.top,
              opacity: 1,
              roomIndex: roomIndex,
              bedIndex: bedIndex,
              initDate: reserv.fechaInicio.substring(0, 10),
              endDate: reserv.fechaFin.substring(0, 10),
              initDay
            });

            for(let i = initMonthDay -1; (i < endMonthDay); i++){

              bed.days[i].busy = reserv.id;
            }
          });
        });
      });

      this.cards = cards;
    },
		setItemDays(){

      this.items.forEach((room, roomIndex) => {

        room.beds.forEach((bed, bedIndex) => {

          let days = [];

          for(let i=0; i<this.date.monthDays; i++){

            days.push({
              day: i+1,
              bedIndex,
              roomIndex,
              background: 'auto',
              selected: false,
              left: 0,
              extend: true,
              busy: null
            });
          }

          bed.days = days;
        });
      });
		},
		initItemDays(){

			let daysObjects = [...document.getElementsByClassName('day')].slice(0, this.date.monthDays);

			let cardDimentions = {
				width: daysObjects[0].offsetWidth,
				height: daysObjects[0].offsetHeight
			}

      this.setCardDimentions(cardDimentions);

      let itemRows = document.getElementsByClassName('item-row');

      let bedsBefore = 0;

      this.items.forEach((room, roomIndex) => {

        room.beds.forEach((bed, bedIndex) => {

          bed.top = (itemRows[bedIndex + bedsBefore].getBoundingClientRect().top - this.container.offsetTop) + this.scrollPos.y + window.scrollY;

          bed.days.forEach((day, indexDay) => {

            day.left = (daysObjects[indexDay].getBoundingClientRect().left + this.scrollPos.x) - this.container.offsetLeft;
          });
        });
        bedsBefore += room.beds.length;
      });
		},
		onScroll(event){

			let container = event.target;
			this.setScrollPos({
				x: container.scrollLeft,
				y: container.scrollTop
			});
		},
		mouseMove(event){

			this.setMousePos({
				x: (event.pageX - this.container.offsetLeft) + this.scrollPos.x,
				y: (event.pageY - this.container.offsetTop) + this.scrollPos.y
			});
    },
		dayMouseDown(e, day){

			this.lengthCard = 1;
			day.background = 'yellow';
			day.selected = true;
			this.startDay = {
				day,
				object: e.target
			};
			this.endDay = {
				day,
				object: e.target
			};

		},
		dayMouseEnter(e, day){

			if(this.mouseDown && this.startDay != null && this.startDay.day.bedIndex == day.bedIndex){

				if(day.selected){

					let bed = this.items[day.roomIndex].beds[day.bedIndex];
					let dayIndex = bed.days.indexOf(day);
					let startDayIndex = bed.days.indexOf(this.startDay.day);
					let endDayIndex = bed.days.indexOf(this.endDay.day);
					let nextDay = startDayIndex > endDayIndex
						? bed.days[dayIndex - 1] : bed.days[dayIndex + 1];

					nextDay.background = 'none';
					nextDay.selected = false;
					this.lengthCard--;

					this.endDay = {
						day,
						object: e.target
					}
					return;
				}

				day.background = 'yellow';
				day.selected = true;
				this.lengthCard++;

				this.endDay = {
					day,
					object: e.target
				}
				return;
			}
		},
		itemRowMouseOut(e){

			if(this.dragingObject == null && this.extendingObject.object == null){

				this.setMouseDown(false);
			}
		}
	},
	watch:{

		date(){

			if(this.isMounted){

				this.setItemDays();
				this.initItemDays();
			}
    }
	}
}
</script>

<style lang="scss">

// @import 'bootstrap';
@import './calendario';

</style>
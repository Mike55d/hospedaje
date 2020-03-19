<template>
<div class="calendario">

  <div @mousemove="mouseMove" @scroll="onScroll" id="calendar-container" class="container table-responsive">
    <controls></controls>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>items</th>
          <th v-for="day of date.monthDays" :key="'day-' + day">{{ day }}</th>
        </tr>
      </thead>
      <tbody>
        <tr @mouseleave="itemRowMouseOut" class="item-row" v-for="(item, index) of items" :key="'item-' + index">
          <td>{{ item.name }}</td>
          <td class="noselect day" v-for="(day, index) of item.days" :key="'item-day-' + index" 
            @mousedown="dayMouseDown($event, day)"
            @mouseenter="dayMouseEnter($event, day)"
            :style="{
              background: day.background
            }">pop</td>
        </tr>
      </tbody>
    </table>
    <card
      v-for="(card, index) of cards"
      :card="card"
      :key="'card-' + index"></card>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="color" v-model="cardColor" @change="changeCardColor">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="cube" @mousedown="boxDragStart"></div> -->
</div>
</template>

<script>

import storeFunctions from './storeFunctions';
import mouseEventListeners from './mouseEventListeners';

export default {
  mixins: [
    storeFunctions,
    mouseEventListeners
  ],
  components: {
    'card': require('./card.vue').default,
    'controls': require('./controls.vue').default
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
      cardColor: ''
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

    console.log('mounted');

    this.setContainer(document.getElementById('calendar-container'))
    document.body.addEventListener('mousedown', (e) => {

      this.setMouseDown(true);
    });

    document.body.addEventListener('mouseup', (e) => {

      this.setMouseDown(false);
    });

    document.addEventListener('mouseleave', (e) => {

      this.setDragingObject(null);
    });

    this.initItemDays();
    this.isMounted = true;

  },
  methods: {

    setItemDays(){

      this.items.forEach((item, index) => {

        let days = [];

        for(let i=0; i<this.date.monthDays; i++){

          days.push({
            day: i+1,
            itemIndex: index,
            background: 'auto',
            selected: false,
            left: 0,
            extend: true
          });
        }
        item.days = days;
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
      itemRows = [...itemRows].slice(0, itemRows.length);

      itemRows.forEach((itemRow, index) => {

        this.items[index].top = (itemRow.getBoundingClientRect().top - this.container.offsetTop) + this.scrollPos.y;
        this.items[index].days.forEach((day, index) => {

          day.left = (daysObjects[index].getBoundingClientRect().left + this.scrollPos.x) - this.container.offsetLeft;
        });
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

      if(this.mouseDown && this.startDay != null && this.startDay.day.itemIndex == day.itemIndex){

        if(day.selected){

          let item = this.items[day.itemIndex];
          let dayIndex = item.days.indexOf(day);
          let startDayIndex = item.days.indexOf(this.startDay.day);
          let endDayIndex = item.days.indexOf(this.endDay.day);
          let nextDay = startDayIndex > endDayIndex
            ? item.days[dayIndex - 1] : item.days[dayIndex + 1];

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

      if(this.dragingObject == null && this.extendingObject == null){

        this.setMouseDown(false);
      }
    },
    changeCardColor(){

      console.log(this.cardColor);
      this.cards[this.cards.length - 1].background = this.cardColor;
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

@import 'bootstrap';
@import './calendario';

</style>
<template>
  <div class="controls">
    <button class="btn btn-primary btn-prev-month" type="button" @click="previusMonth">&lt;</button>
    <h3 class="date">{{ date.month |formatMonth }} de {{ date.year }} ({{ date.monthDays }} dias)  </h3>
    <button class="btn btn-primary btn-next-month" type="button" @click="nextMonth">&gt;</button>
  </div>
</template>

<script>

import storeFunctions from './storeFunctions';
import moment from 'moment';


export default {
  mixins: [
    storeFunctions
  ],
  methods: {

    previusMonth(){

      let year = this.date.year;
      let month = this.date.month;
      let monthDays;

      if(this.date.month == 0){

        year -= 1;
        month = 11;
      }else{

        month -= 1
      }

      monthDays = new Date(year, month + 1, 0).getDate();
      this.setDate({
        year,
        month,
        monthDays
      });

      this.$emit('change-date');
    },
    nextMonth() {

      let year = this.date.year;
      let month = this.date.month;
      let monthDays;

      if(this.date.month == 11){

        month = 1;
        year += 1;
      }else{

        month += 1
      }

      monthDays = new Date(year, month + 1, 0).getDate();
      this.setDate({
        year,
        month,
        monthDays
      });

      this.$emit('change-date');
    }
  },
  filters:{
    formatMonth: function(value){
      moment.locale('es');
      if (value) {
        return moment().month(value).format('MMMM').toUpperCase();
      }
    }
  }
}
</script>

<template>
  <div class="controls">
    <button type="button" class="btn-prev-month" @click="previusMonth">&lt;</button>
    Year {{ date.year }}, Month {{ date.month }}, MonthDays {{ date.monthDays }}
    <button type="button" class="btn-next-month">&gt;</button>
  </div>
</template>

<script>

import storeFunctions from './storeFunctions';

export default {
  mixins: [
    storeFunctions
  ],
  methods: {

    previusMonth(){

      let year = this.date.year;
      let month = this.date.month;
      let monthDays;

      console.log('prev');
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
    }
  }
}
</script>

<style>

</style>
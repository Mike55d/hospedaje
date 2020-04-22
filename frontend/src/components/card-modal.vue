<template>

<div>
  <div class="modal fade bs-example-modal-lg" id="my-modal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" id="close-modal" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
        </div>
        <div class="modal-body">
          <div class="row modal-row">
            <div class="col-md-4 col-sm-4">
              <h2>Filters</h2>
              <ul class="filters">
                <li v-for="(filter, index) of filters" :key="'filter-' + index">{{ filter }}</li>
              </ul>
            </div>
            <div class="col-md-4 col-sm-4">
              <h2>Users</h2>
              <ul class="users">
                <li v-for="(user, index) of users" :key="'user-' + index">{{ user }}</li>
              </ul>
            </div>
            <div class="col-md-4 col-sm-4">
              <h2>User Data</h2>
              <ul class="user-data">
                Name: User 1
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
          <button @click="cancel">Cancel</button>
          <button @click="editCard">Accept</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <button class="btn btn-default" id="open-modal" :style="{display: 'none'}" data-toggle="modal"
    data-target=".bs-example-modal-lg">test</button>
</div>
</template>

<script>

import storeFunctions from './storeFunctions';

export default {

  mixins: [
    storeFunctions
  ],
  props: ['show', 'card'],
  data: function(){

    return {

      filters: [
        'Groups',
        'Users'
      ],
      users: [
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1',
        'User 1'
      ],
      localCard: null,
      initialDate: null,
      endDate: null
    }
  },
  methods: {

    cancel(){

      this.$emit('cancel');
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
        let daysInMonth;
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

          console.log(lastInitMonthDay, lastEndMonthDay);
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
    }
  },
  watch: {

    show(){

      this.localCard = {...this.card};
      this.initialDate = this.card.initDate;
      this.endDate = this.card.endDate;
      this.openModal();
    }
  }
}
</script>
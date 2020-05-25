const axios = require('axios').default;

export default {

  methods: {

    openModal(){

      this.editCard = this.cards[this.cards.length -1];
      this.showCardModal = !this.showCardModal;
    }
  },
  watch: {

    mouseDown(){

      if(!this.mouseDown){

        document.body.style.cursor = 'initial';
        //Move
        if(this.dragingObject != null){
          let dragingObject = this.dragingObject;

          if(this.destiny == null || this.destiny.busy){
            this.moveDragingObject({
              left: this.origin.left,
              top: this.origin.top,
              bedIndex: this.origin.bedIndex,
              roomIndex: this.origin.roomIndex,
              initDay: this.origin.day
            });

            if(this.destiny != null){

              let bed = this.items[this.destiny.roomIndex].beds[this.destiny.bedIndex];
              let daysOutMonth = (this.destiny.day.left - this.destiny.left)/this.cardDimentions.width;
              let daysInMonth = dragingObject.lengthCard - daysOutMonth;

              for(let i = this.destiny.day.day -1; i < this.destiny.day.day + daysInMonth -1; i++){
                bed.days[i].background = 'none';
              }
              this.setDestiny(null);
            }
          }else{
            this.moveDragingObject({
              left: this.destiny.left,
              top: this.destiny.top,
              bedIndex: this.destiny.bedIndex,
              roomIndex: this.destiny.roomIndex,
              initDay: this.destiny.day
            });

            this.setDestiny(null);
            this.$emit('move', dragingObject);
          }
        }

        //Extend
        if(this.extendingObject.object != null){

          if(this.daysExtend != 0){

            let card = this.extendingObject.object;
            let newCardLength =  this.daysExtend + card.lengthCard;
            let lastLeftPos = this.extendingObject.lastLeftPos;
            let left = lastLeftPos;
            let bed = this.items[card.roomIndex].beds[card.bedIndex];
            let daysInMonth = card.lengthCard;

            if(card.left < card.initDay.left){

              daysInMonth = card.lengthCard - ((card.initDay.left - card.left) / this.cardDimentions.width);
            }

            if(this.extendingObject.direction == 'left'){

              for(let i = 1; i <= Math.abs(this.daysExtend); i++){

                if(this.daysExtend > 0){

                  bed.days[card.initDay.day -1 - i].busy = card.id;
                }else {

                  bed.days[card.initDay.day -2 + i].busy = null;
                }
              }
              left = lastLeftPos - (this.daysExtend * this.cardDimentions.width);
            }else{

              for(let i = 1; i <= Math.abs(this.daysExtend); i++){

                if(this.daysExtend > 0){

                  bed.days[card.initDay.day + daysInMonth -2 + i].busy = card.id;
                }else {

                  bed.days[card.initDay.day + daysInMonth -1 - i].busy = null;
                }
              }
            }

            let extendingObject = this.extendingObject.object;
            this.extendObject({
              width: newCardLength * this.cardDimentions.width,
              lengthCard: newCardLength,
              left,
            });
            this.daysExtend = 0;
            this.$emit('extend', card);
          }else{

            this.extendObject({
              width: this.origin.width,
              lengthCard: this.extendingObject.object.lengthCard,
              left: this.origin.left
            });
          }
        }
      }

      //Create
      if(this.startDay != null && !this.mouseDown){

        let bed = this.items[this.startDay.day.roomIndex].beds[this.startDay.day.bedIndex];

        bed.days.forEach(day => {

          day.background = 'none';
          day.selected = false;
        });

        let startDayIndex = bed.days.indexOf(this.startDay.day);
        let endDayIndex = bed.days.indexOf(this.endDay.day);

        for(let i=startDayIndex; i<=endDayIndex; i++){

          bed.days[i].busy = this.cards.length +1;
        }

        let dayRef = startDayIndex > endDayIndex
          ? this.endDay : this.startDay;

        let rect = dayRef.object.getBoundingClientRect();

        let newCard = {
          id: this.cards.length +1,
          name: 'Card x' + this.cards.length,
          lengthCard: this.lengthCard,
          background: 'blue',
          width: this.cardDimentions.width * this.lengthCard,
          left: rect.left - this.container.offsetLeft + this.scrollPos.x,
          top: rect.top - this.container.offsetTop + this.scrollPos.y + window.scrollY,
          opacity: .7,
          roomIndex: this.startDay.day.roomIndex,
          bedIndex: this.startDay.day.bedIndex,
          initDate: `${this.date.year}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(this.date.month + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(dayRef.day.day)}`,
          endDate: `${this.date.year}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(this.date.month + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(dayRef.day.day + this.lengthCard -1)}`,
          initDay: dayRef.day,
          infoMargin: '10px'
        };

        this.cards.push(newCard);
        this.openModal();
        this.$emit('create', newCard);
        axios.post('http://127.0.0.1:8000/calendario/newReserva', {
          cama: this.items[newCard.roomIndex].beds[newCard.bedIndex].id,
          persona: 1,
          fechaInicio: newCard.initDate,
          fechaFin: newCard.endDate
        }).then(res => {

          console.log(res);
        });
        this.setDestiny(null);
        this.lengthCard = 0;
        this.startDay = null;
        this.endDay = null;
      }
    },
    mousePos(){

      const EXTEND_BTN_WIDTH = 7;

      let midHeightCard = this.cardDimentions.height / 2;
      let midWidthCard = this.cardDimentions.width / 2;
      let mousePos = this.mousePos;

      //Move
      if(this.dragingObject != null){

        let dragingObject = this.dragingObject;

        this.moveShadowDragingObject({
          top: dragingObject.top + ((this.lastMousePos.y - mousePos.y) * -1),
          left: dragingObject.left + ((this.lastMousePos.x - mousePos.x) * -1)
        });

        this.setLastMousePos(mousePos);
        let mouseBoxPosTop = mousePos.y - this.mouseBox.top;
        let mouseBoxPosBottom = mousePos.y + this.mouseBox.bottom;
        let mouseBoxPosLeft = mousePos.x - this.mouseBox.left;
        let mouseBoxPosRight = mousePos.x + this.mouseBox.right;
        let destinyDays = [];
        let busy = false;
        let destiny = {
          left: null,
          top: null,
          roomIndex: null,
          bedIndex: null,
          day: null
        };

        this.items.forEach((room, roomIndex) => {

          room.beds.forEach((bed, bedIndex) => {

            if((
              (mouseBoxPosTop <= (bed.top + midHeightCard)) &&
              (mouseBoxPosBottom >= (bed.top + this.cardDimentions.height)) ||
              ((mouseBoxPosBottom >= (bed.top + midHeightCard)) &&
              mouseBoxPosTop <= bed.top))){

              destiny.top = bed.top;
              destiny.roomIndex = roomIndex;
              destiny.bedIndex = bedIndex;

              bed.days.forEach((day, dayIndex) => {

                day.background = 'none';

                if(
                  mouseBoxPosLeft <= (day.left + midWidthCard -1) &&
                  mouseBoxPosRight >= (day.left + midWidthCard +1)){

                  day.background = 'red';
                  destinyDays.push(day);
                  if(day.busy != null && day.busy != dragingObject.id){

                    busy = true;
                  }
                }
              });

              if(destinyDays.length > 0) {

                if(mouseBoxPosLeft < bed.days[0].left) {

                  let outDays = Math.round((bed.days[0].left - mouseBoxPosLeft) / this.cardDimentions.width);
                  destiny.left = bed.days[0].left - (outDays * this.cardDimentions.width);
                }else{

                  destiny.left = destinyDays[0].left;
                }

                destiny.day = destinyDays[0];
                destinyDays = [];
                return;
              }
            }

            bed.days.forEach((day, index) => {

              day.background = 'none';
            });

            return;

          });
        });

        this.setDestiny({
          left: destiny.left,
          top: destiny.top,
          roomIndex: destiny.roomIndex,
          bedIndex: destiny.bedIndex,
          day: destiny.day,
          busy
        });
      }

      //Extend
      if(this.extendingObject.object != null){

        let extendingObject = this.extendingObject.object;
        let bed = this.items[extendingObject.roomIndex].beds[extendingObject.bedIndex];
        let mouseBoxPos = (extendingObject.left + extendingObject.width);
        let daysExtend = 0;
        let initDayIndex = null;
        let extendingObjectRight = extendingObject.left + (extendingObject.lengthCard * this.cardDimentions.width);
        let extendingObjectLeft = extendingObject.initDay.left;
        let busy = false;

        switch(this.extendingObject.direction){

          case 'right':

            extendingObject.width += ((this.lastMousePos.x - mousePos.x) * -1);
            bed.days.forEach((day, dayIndex) => {

              day.background = 'none';

              //extend
              if(mousePos.x > extendingObjectRight){
                if(
                  day.left >= extendingObjectRight &&
                  (mousePos.x >= ((day.left + midWidthCard) - (EXTEND_BTN_WIDTH)))){

                  day.background = 'yellow';
                  if(day.busy != null){

                    busy = true;
                  }
                  daysExtend++;
                }
              // Shorten
              } else {

                if(
                  day.left < extendingObjectRight &&
                  day.left >= extendingObject.left &&
                  (day.left + midWidthCard) > mousePos.x &&
                  (day.left + this.cardDimentions.width) > mousePos.x) {

                    if((extendingObject.lengthCard + daysExtend) > 1){

                      daysExtend--;
                    }
                }
              }

            });
            this.setLastMousePos(mousePos);
            break;

          case 'left':

            var movement = (this.lastMousePos.x - mousePos.x);

            if(movement < 0){

              if(extendingObject.left <= (extendingObject.initDay.left + ((extendingObject.lengthCard -1) * this.cardDimentions.width))){

                extendingObject.width += movement;
                extendingObject.left -= movement;
              }
            }else{

              if(mousePos.x < extendingObject.left){

                extendingObject.width += movement;
                extendingObject.left -= movement;
              }
            }

            bed.days.forEach((day, dayIndex) => {

              day.background = 'none';

              // Extend
              if(mousePos.x < extendingObjectLeft){

                if(
                  (day.left + midWidthCard + EXTEND_BTN_WIDTH) > mousePos.x &&
                  day.left < extendingObjectLeft ) {

                  day.background = 'yellow';
                  daysExtend++;
                  if(day.busy != null){

                    busy = true;
                  }
                  if(initDayIndex == null) {

                    initDayIndex = dayIndex;
                  }
                }
              //Shorten
              }else{

                if(
                  (day.left + midWidthCard) < mousePos.x &&
                  day.left >= extendingObjectLeft) {

                  if((extendingObject.lengthCard + daysExtend) > 1){

                    daysExtend--;
                    initDayIndex = dayIndex +1;
                  }
                }
              }
            });
            this.setLastMousePos(mousePos);
            break;
        }

        if(!busy){

          this.daysExtend = daysExtend;
          this.extendingObject.initDay = initDayIndex != null && initDayIndex != this.date.monthDays ? bed.days[initDayIndex] : this.extendingObject.object.initDay;
        }
      }
    }
  }
}
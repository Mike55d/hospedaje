import $ from 'jquery';

export default {

  watch: {

    mouseDown(){

      if(!this.mouseDown){

        //Move
        if(this.dragingObject != null){

          this.moveDragingObject({
            left: this.destiny.left,
            top: this.destiny.top,
            itemIndex: this.destiny.itemIndex
          });
        }

        //Extend
        if(this.extendingObject.object != null){

          let daysToExtend = (this.daysExtend - (this.extendingObject.object.lengthCard));
          let newCardLength =  daysToExtend + this.extendingObject.object.lengthCard;
          let lastLeftPos = this.extendingObject.lastLeftPos;
          let left = lastLeftPos;

          if(this.extendingObject.direction == 'left'){

            left = lastLeftPos - (daysToExtend * this.cardDimentions.width);
          }

          console.log(daysToExtend);
          this.extendObject({
            width: newCardLength * this.cardDimentions.width,
            lengthCard: newCardLength,
            left
          });
        }
      }

      //Create
      if(this.startDay != null && !this.mouseDown){

        let item = this.items[this.startDay.day.itemIndex];

        item.days.forEach(day => {

          day.background = 'none';
          day.selected = false;
        });

        let startDayIndex = item.days.indexOf(this.startDay.day);
        let endDayIndex = item.days.indexOf(this.endDay.day);
        let dayRef = startDayIndex > endDayIndex
          ? this.endDay : this.startDay;

        let rect = dayRef.object.getBoundingClientRect();

        this.cards.push({
          id: this.cards.length + 1,
          name: 'Card x' + this.cards.length,
          lengthCard: this.lengthCard,
          background: 'blue',
          width: this.cardDimentions.width * this.lengthCard,
          left: rect.left - this.container.offsetLeft + this.scrollPos.x,
          top: rect.top - this.container.offsetTop + this.scrollPos.y,
          opacity: 1,
          itemIndex: this.startDay.day.itemIndex
        });

        this.lengthCard = 0;
        this.startDay = null;
        this.endDay = null;

        $('#exampleModal').modal('show');

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
        let colorearIndex = null;

        this.items.forEach((item, itemIndex) => {

          if((
            (mouseBoxPosTop <= (item.top + midHeightCard -1)) &&
            (mouseBoxPosBottom >= (item.top + this.cardDimentions.height)) ||
            ((mouseBoxPosBottom >= (item.top + midHeightCard +1)) &&
            mouseBoxPosTop <= item.top))){

            this.destiny.top = item.top;
            this.destiny.itemIndex = itemIndex;
            item.days.forEach((day, dayIndex) => {

              day.background = 'none';

              if((
                (mouseBoxPosLeft <= (day.left + midWidthCard -1)) &&
                (mouseBoxPosRight >= (day.left + this.cardDimentions.width)) ||
                ((mouseBoxPosRight >= (day.left + midWidthCard +1)) &&
                mouseBoxPosLeft <= day.left))){

                colorearIndex = dayIndex;
                this.destiny.left = day.left;
              }

              if(colorearIndex != null){

                if(dayIndex < (this.dragingObject.lengthCard + colorearIndex)){

                  day.background = 'red';
                  return;
                }

                colorearIndex = null;
              }
            });

            return;
          }

          item.days.forEach((day, index) => {

            day.background = 'none';
          });

          return;
        });
      }

      //Extend
      if(this.extendingObject.object != null){

        let extendingObject = this.extendingObject.object;
        let item = this.items[extendingObject.itemIndex];
        let mouseBoxPos = (extendingObject.left + extendingObject.width);
        let daysExtend = 0;

        switch(this.extendingObject.direction){

          case 'right':

            extendingObject.width += ((this.lastMousePos.x - mousePos.x) * -1);

            item.days.forEach((day, dayIndex) => {

              day.background = 'none';

              if((mousePos.x >= ((day.left + midWidthCard) - (EXTEND_BTN_WIDTH))) &&
                extendingObject.left <= day.left){

                day.background = 'yellow';
                daysExtend++;
              }
            });
            this.setLastMousePos(mousePos);
            this.daysExtend = daysExtend;
            break;

          case 'left':

            var movement = (this.lastMousePos.x - mousePos.x);
            extendingObject.width += movement;
            extendingObject.left -= movement;

            item.days.forEach((day, dayIndex) => {

              day.background = 'none';

              if((mousePos.x <= ((day.left + midWidthCard) + (EXTEND_BTN_WIDTH))) &&
                (extendingObject.left + extendingObject.width) > day.left){

                day.background = 'yellow';
                daysExtend++;
              }
            });
            this.setLastMousePos(mousePos);
            this.daysExtend = daysExtend;
            break;
        }
      }
    }
  }
}
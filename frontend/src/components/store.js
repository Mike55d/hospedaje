import Vuex from 'vuex';

export default new Vuex.Store({
  state: {
    date: {
      month: null,
      monthDays: null,
      year: null
    },
    container: null,
    mouseDown: false,
    mousePos: {
      x: 0,
      y: 0
    },
    scrollPos: {
      x: 0,
      y: 0
    },
    lastMousePos: null,
    cardDimentions: null,
    dragingObject: null,
    extendingObject: {
      object: null,
      direction: null,
      lastLeftPos: null,
      initDay: null
    },
    mouseBox: {
      top: 0,
      bottom: 0,
      left: 0,
      right: 0
    },
    origin: null,
    destiny: null,
    // items: [
    //   {
    //     id: 1,
    //     number: 1,
    //     beds: [
    //       {
    //         id: 1,
    //         name: '1',
    //         days: [],
    //         top: 0
    //       },
    //       {
    //         id: 2,
    //         name: '2',
    //         days: [],
    //         top: 0
    //       },
    //       {
    //         id: 3,
    //         name: '3',
    //         days: [],
    //         top: 0
    //       },
    //     ]
    //   },
    //   {
    //     id: 2,
    //     number: 2,
    //     beds: [
    //       {
    //         id: 4,
    //         name: '4',
    //         days: [],
    //         top: 0
    //       },
    //       {
    //         id: 5,
    //         name: '5',
    //         days: [],
    //         top: 0
    //       },
    //       {
    //         id: 6,
    //         name: '6',
    //         days: [],
    //         top: 0
    //       },
    //     ]
    //   },
    //   {
    //     id: 3,
    //     number: 3,
    //     beds: [
    //       {
    //         id: 7,
    //         name: '7',
    //         days: [],
    //         top: 0
    //       },
    //       {
    //         id: 8,
    //         name: '8',
    //         days: [],
    //         top: 0
    //       }
    //     ]
    //   }
    // ]
    items: []
  },
  mutations: {

    setItems(state, newItems){

      state.items = newItems;
    },
    setDate(state, newDate){

      state.date = newDate;
    },
    setContainer(state, newContainer){

      state.container = newContainer;
    },

    setMouseDown(state, newMouseDown){

      state.mouseDown = newMouseDown;
    },
    setMousePos(state, newMousePos){

      state.mousePos = newMousePos;
    },
    setScrollPos(state, newScrollPos){

      state.scrollPos = newScrollPos;
    },
    setLastMousePos(state, newLastMousePos){

      state.lastMousePos = newLastMousePos;
    },
    setCardDimentions(state, newCardDimentions){

      state.cardDimentions = newCardDimentions;
    },
    setDragingObject(state, newDragingObject){

      if(newDragingObject != null){

        state.dragingObject = newDragingObject;
        state.dragingObject.opacity = .3;
      }
    },
    setExtendingObject(state, newExtendingObject){

      state.extendingObject = newExtendingObject;
    },
    setMouseBox(state, newMouseBox){

      state.mouseBox = newMouseBox;
    },
    setDestiny(state, newDestiny){

      state.destiny = newDestiny;
    },
    setOrigin(state, newOrigin){

      state.origin = newOrigin;
    },
    moveShadowDragingObject(state, payload){

      state.dragingObject.left = payload.left;
      state.dragingObject.top = payload.top;
    },
    moveDragingObject(state, payload){

      let object = state.dragingObject;
      let initDate;
      let endDate;
      let infoMargin;
      let bed = state.items[object.roomIndex].beds[object.bedIndex];
      const MS_OF_THE_DAY = (1000 * 3600 * 24);
      let monthFirstDate = new Date(state.date.year, state.date.month, 1);
      let daysInMonth = object.lengthCard;
      let lastInitDateSplit = object.initDate.split('-');
      let lastInitDate = new Date(lastInitDateSplit[0], lastInitDateSplit[1] -1, lastInitDateSplit[2]);
      let daysOutMonth = (monthFirstDate.getTime() - lastInitDate.getTime()) / MS_OF_THE_DAY;

      if(daysOutMonth > 0){

        daysInMonth = object.lengthCard - daysOutMonth;
      }

      for(let i = object.initDay.day -1; (i < daysInMonth + object.initDay.day -1) && (i < state.date.monthDays); i++){

        bed.days[i].busy = null;
      }

      object.opacity = .7;
      object.left = payload.left;
      object.top = payload.top;
      object.bedIndex = payload.bedIndex;
      object.roomIndex = payload.roomIndex;
      object.initDay = payload.initDay;
      infoMargin = '10px';
      let date = new Date(state.date.year, state.date.month, payload.initDay.day);

      if(object.left < object.initDay.left){

        date = new Date(date.getFullYear(), date.getMonth(), 1);
        let leftDays = (payload.initDay.left - payload.left) / state.cardDimentions.width;
        date.setDate(date.getDate() - leftDays);
        infoMargin = (object.initDay.left + (object.initDay.left - object.left) + 10) + 'px';
      }

      initDate = `${date.getFullYear()}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(date.getMonth() + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(date.getDate())}`;
      date.setDate(date.getDate() + object.lengthCard - 1);
      endDate = `${date.getFullYear()}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(date.getMonth() + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(date.getDate())}`;

      object.initDate = initDate;
      object.endDate = endDate;
      object.infoMargin = infoMargin;
      bed = state.items[object.roomIndex].beds[object.bedIndex];
      daysOutMonth = Math.round((state.items[0].beds[0].days[0].left - object.left)/ state.cardDimentions.width);
      daysInMonth = object.lengthCard;

      daysInMonth = object.lengthCard;
      lastInitDateSplit = object.initDate.split('-');
      lastInitDate = new Date(lastInitDateSplit[0], lastInitDateSplit[1] -1, lastInitDateSplit[2]);
      daysOutMonth = (monthFirstDate.getTime() - lastInitDate.getTime()) / MS_OF_THE_DAY;

      if(daysOutMonth > 0){

        daysInMonth = object.lengthCard - daysOutMonth;
      }

      for(let i = object.initDay.day -1; (i < daysInMonth + object.initDay.day -1) && (i < state.date.monthDays); i++){

        bed.days[i].busy = object.id;
        bed.days[i].background = 'none';
      }

      state.dragingObject = null;
    },
    extendObject(state, payload){

      let object = state.extendingObject.object;

      object.width = payload.width;
      object.left = payload.left;
      object.lengthCard = payload.lengthCard;
      object.initDay = state.extendingObject.initDay;
      if(state.extendingObject.direction == 'left'){

        let lastMonthDayObj = state.items[0].beds[0].days[state.date.monthDays - 1];

        if((lastMonthDayObj.left + state.cardDimentions.width) <= object.left){

          object.initDate = `${state.date.year}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(state.date.month + 2)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(1)}`;
        }else{

          object.initDate = `${state.date.year}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(state.date.month + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(object.initDay.day)}`;
        }
      }else{

        if(object.left < object.initDay.left){

          let outDays = ((object.initDay.left - object.left) / state.cardDimentions.width);
          let insideDays = object.lengthCard - outDays;

          if(insideDays == 0){

            let lastMonthDay = new Date(state.date.year, state.date.month -1, 0).getDate();
            object.endDate = `${state.date.year}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(state.date.month)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(lastMonthDay)}`;
          }else{

            object.endDate = `${state.date.year}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(state.date.month + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(object.lengthCard - outDays)}`;
          }
        }else {

          object.endDate = `${state.date.year}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format(state.date.month + 1)}-${new Intl.NumberFormat("en-IN", {minimumIntegerDigits: 2}).format((object.lengthCard -1) + state.extendingObject.initDay.day)}`;
        }
      }

      state.extendingObject.object = null;
    }
  }
});
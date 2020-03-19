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
      lastLeftPos: null
    },
    mouseBox: {
      top: 0,
      bottom: 0,
      left: 0,
      right: 0
    },
    destiny: null,
    items: [
      {
        id: 1,
        name: 'Cama 1',
        days: [],
        top: 0
      },
      {
        id: 2,
        name: 'Cama 2',
        days: [],
        top: 0
      },
      {
        id: 3,
        name: 'Cama 3',
        days: [],
        top: 0
      },
      {
        id: 4,
        name: 'Cama 4',
        days: [],
        top: 0
      },
      {
        id: 5,
        name: 'Cama 5',
        days: [],
        top: 0
      },
      {
        id: 6,
        name: 'Cama 6',
        days: [],
        top: 0
      },
      {
        id: 7,
        name: 'Cama 7',
        days: [],
        top: 0
      },
      {
        id: 8,
        name: 'Cama 8',
        days: [],
        top: 0
      }
    ]
  },
  mutations: {

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

      state.dragingObject = newDragingObject;
      state.dragingObject.opacity = .3;
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
    moveShadowDragingObject(state, payload){

      state.dragingObject.left = payload.left;
      state.dragingObject.top = payload.top;
    },
    moveDragingObject(state, payload){

      state.dragingObject.opacity = 1;
      state.dragingObject.left = payload.left;
      state.dragingObject.top = payload.top;
      state.dragingObject.itemIndex = payload.itemIndex;
      state.dragingObject = null;
    },
    extendObject(state, payload){

      let object = state.extendingObject.object;
      object.width = payload.width;
      object.left = payload.left;
      object.lengthCard = payload.lengthCard;
      state.extendingObject.object = null;
    }
  }
});
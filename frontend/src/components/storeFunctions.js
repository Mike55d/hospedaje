import { mapState, mapMutations } from 'vuex';

export default {
  methods: {

    ...mapMutations([
      'setItems',
      'setDate',
      'setContainer',
      'setMouseDown',
      'setMousePos',
      'setScrollPos',
      'setLastMousePos',
      'setCardDimentions',
      'setDragingObject',
      'setExtendingObject',
      'setMouseBox',
      'setOrigin',
      'setDestiny',
      'moveDragingObject',
      'moveShadowDragingObject',
      'extendObject'
    ])
  },
  computed: {

    ...mapState([
      'date',
      'container',
      'items',
      'days',
      'mouseDown',
      'mousePos',
      'scrollPos',
      'lastMousePos',
      'cardDimentions',
      'dragingObject',
      'extendingObject',
      'origin',
      'destiny',
      'mouseBox'
    ])
  }
}
<template>
  <div v-bind:class="[card.status ? card.status : 'pendiente']" class="cards"
    :style="{
    width: card.width + 'px',
    height: cardDimentions.height + 'px',
    top: card.top + 'px',
    left: card.left + 'px',
    }"
    @mousedown="boxDragStart(card)"
    @dblclick="test">
    <div class="info-card noselect" :style="{
      'margin-left': card.infoMargin,
      display: 'block',
      color: 'white',
      'font-size': '11px',
      'padding': '5px',
      'font-weight': 'bold'
    }">
    <div v-if="card.persona" style="height:38px;width:200px">
      {{card.initDate}} | {{ card.endDate }}<br>{{card.persona.tratamiento}}. {{card.persona.nombre}} | {{card.persona.grupo.nombre}}
    </div>
    </div>
    <div @mousedown.stop="boxExtendRight($event, card)" class="card-right-btn"></div>
    <div @mousedown.stop="boxExtendLeft($event, card)" class="card-left-btn"></div>
  </div>
</template>

<script>

import storeFunctions from './storeFunctions';

export default {

  mixins: [

    storeFunctions
  ],
  props: [
    'card'
  ],
  methods: {

    test(){

      this.$emit('edit');
    },
    boxDragStart(card){

      this.setOrigin({
        left: card.left,
        top: card.top,
        roomIndex: card.roomIndex,
        bedIndex: card.bedIndex,
        day: card.initDay
      });

      this.setLastMousePos(this.mousePos);
      let dragingObject = card;
      let mouseBox = {
        top: this.mousePos.y - card.top,
        bottom: (card.top + this.cardDimentions.height) - this.mousePos.y,
        left: this.mousePos.x - card.left,
        right: card.left + (this.cardDimentions.width * card.lengthCard) - this.mousePos.x
      }

      this.setMouseBox(mouseBox);
      this.setDragingObject(dragingObject);
    },
    boxExtendRight(e, card){

      document.body.style.cursor = 'col-resize';
      this.setMouseDown(true);

      this.setOrigin({
        left: card.left,
        top: card.top,
        roomIndex: card.roomIndex,
        bedIndex: card.bedIndex,
        day: card.initDay,
        width: card.width
      });

      this.setExtendingObject({
        object: card,
        direction: 'right',
        lastLeftPos: card.left,
        initDay: card.initDay
      });
      this.setLastMousePos(this.mousePos);
    },
    boxExtendLeft(e, card){

      this.setOrigin({
        left: card.left,
        top: card.top,
        roomIndex: card.roomIndex,
        bedIndex: card.bedIndex,
        day: card.initDay,
        width: card.width
      });

      document.body.style.cursor = 'col-resize';
      this.setMouseDown(true);
      this.setExtendingObject({
        object: card,
        direction: 'left',
        lastLeftPos: card.left,
        initDay: card.initDay
      });
      this.setLastMousePos(this.mousePos);
    }
  }
}
</script>

<style>

</style>
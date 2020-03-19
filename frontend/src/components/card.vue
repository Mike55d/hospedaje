<template>
<div class="cards"
	:style="{
	background: card.background,
	width: card.width + 'px',
	height: cardDimentions.height + 'px',
	top: card.top + 'px',
	left: card.left + 'px',
	opacity: card.opacity
	}"
	@mousedown="boxDragStart(card)">
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

    boxDragStart(card){

      this.setDestiny({
        left: card.left,
        top: card.top,
        itemIndex: card.itemIndex
      });
      this.setLastMousePos(this.mousePos);
      let dragingObject = card;
      let mouseBox = {
        top: this.mousePos.y - card.top,
        bottom: (card.top + this.cardDimentions.height) - this.mousePos.y,
        left: this.mousePos.x - card.left,
        right: (card.left + this.cardDimentions.width) - this.mousePos.x
      }

      this.setMouseBox(mouseBox);
      this.setDragingObject(dragingObject);
    },
    boxExtendRight(e, card){

      this.setMouseDown(true);
      this.setExtendingObject({
        object: card,
        direction: 'right',
        lastLeftPos: card.left
      });
      this.setLastMousePos(this.mousePos);
    },
    boxExtendLeft(e, card){

      this.setMouseDown(true);
      this.setExtendingObject({
        object: card,
        direction: 'left',
        lastLeftPos: card.left
      });
      this.setLastMousePos(this.mousePos);
    }
  }
}
</script>

<style>

</style>
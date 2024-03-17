<template>
    <div v-if="showModal" class="settings-modal" ref="target"  :style="'transform: translate(-50%, -50%); width:'+width+'px;'">
      <div class="settings-modal__header" ref="targetHeader">
        <h2>{{ modalTitle }}</h2>
      </div>
      <div class="settings-modal__body">
        <slot></slot>
      </div>
      <div class="settings-modal__footer">
        <button type="button" class="button button-danger" @click="$emit('update')"><i class="bi bi-check-lg"></i></button>
      </div>
    </div>
    <Moveable
      className="moveable"
      :target="target"
      :dragTarget="targetHeader"
      :draggable="true"
      :scalable="true"
      :resizable="true"
      :rotatable="false"
      :hideDefaultLines="true"
      :keepRatio="false"
      :checkInput="true"
      :throttleResize="1"
      :renderDirections="['se']"
      :scroll-sensitivity="200"
      :force-fallback="true"
      :edge="['n', 's', 'w', 'e']"
      :origin="false"
      @drag="onDrag"
      @resize="onResize"
    />
</template>

<script setup>
import Moveable from "vue3-moveable";
import { ref, watch } from 'vue'

const props = defineProps({
    show: Boolean,
    modalTitle: {
        type: String,
        default: 'Settings'
    },
    width: {
        type: Number,
        default: 500
    },
});
let showModal = ref(props.show);
const target = ref(null)
const targetHeader = ref(null)

const emits = defineEmits(['update']);

function onDrag(e) {
  e.target.style.transform = e.transform;

}

function onResize(e) {
  e.target.style.width = `${e.width}px`;
  e.target.style.height = `${e.height}px`;
  e.target.style.transform = e.drag.transform;
}

watch(() => props.show, (value) => {
    showModal.value = value;
});

</script>
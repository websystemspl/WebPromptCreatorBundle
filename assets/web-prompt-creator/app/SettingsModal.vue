<template>
    <Teleport to=".minimized-area" :disabled="isMaximized">
      <div v-if="!isMaximized" class="minimized-area__widget" @click="maximize()">
        <i :class="props.elementData.icon"></i>
        <div>
          <div class="minimized-area__widget-title">
            {{ props.elementData.name }} (#{{ props.elementData.uid }})
          </div>
          <div v-if="props.elementData.settings" class="minimized-area__widget-info">
            {{ props.elementData.settings.title }}
          </div>
        </div>
      </div>
    </Teleport>

    <div v-if="showModal && isMaximized" class="settings-modal" ref="target"  :style="'position: fixed; transform: translate(-50%, -50%); width:'+width+'px;'">
      <div class="settings-modal__header" ref="targetHeader">
        <h2>{{ modalTitle }}</h2>
        <i class="bi bi-arrow-bar-down pointer" @click="minimize()"></i>
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
    elementData: {
        type: Object,
        default: {}
    }
});
let showModal = ref(props.show);
const target = ref(null)
const targetHeader = ref(null)
const isMaximized = ref(true)

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

function minimize() {
  isMaximized.value = false;
}

function maximize() {
  isMaximized.value = true;
}

</script>
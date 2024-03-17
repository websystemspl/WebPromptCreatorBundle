<template>
    <div class="widget">
        <div class="widget__header widget__header--blue">
            <div class="widget__actions">
                <i class="bi bi-gear-fill open" @click="(e) => {showModal = true}"></i>
                <i class="bi bi-copy pointer" @click="contentStore.findAndDuplicateByUID(props.elementData.uid)"></i>
                <i class="bi bi-trash3 close" @click="contentStore.removeElement(props.elementData.uid)"></i>
            </div>
            <div class="widget__title"><i :class="props.elementData.icon"></i> {{ props.elementData.name }} {{ (props.elementData.settings.title) ? "("+props.elementData.settings.title+")" : "" }}</div>
            <div class="widget__info">
                <button class="button button--empty" type="button" @click="props.elementData.openToggle = !props.elementData.openToggle">
                    <i :class="(props.elementData.openToggle) ? 'bi bi-chevron-down' : 'bi bi-chevron-up'"></i>
                </button>                
            </div>
        </div>

        <Transition>
        <div class="widget__body" v-if="props.elementData.openToggle">
            <draggable
                class="dragArea widget__dragArea"
                :list="props.elementData.widgets"
                group="widgets"
                itemKey="id"
            >
                <template #item="{ element, index }">
                    <div
                        class=""
                    >
                        <component :elementData="element" :is="element.component.object"></component>    
                    </div>
                </template>
            </draggable> 
        </div>
        </Transition>
        
        <Teleport to="body">
            <SettingsModal :show="showModal" @update="(e) => {showModal = false}">
                <p>UID: #{{ props.elementData.uid }}</p>
                <div class="wpc-form wpc-form--full-width">
                    <div class="wpc-form__field">
                        <label for="" class="wpc-form__label">Title</label>
                        <div class="wpc-form__input">
                            <input type="text" v-model="props.elementData.settings.title" @input="(e) => { props.elementData.settings.title = e.target.value }" />
                        </div>
                    </div>
                </div>
            </SettingsModal>
        </Teleport>
    </div>
</template>

<script setup>
    import { ref, watch, defineAsyncComponent, onBeforeUnmount } from 'vue';
    import { contentStore } from './../store/content.js'
    import draggable from "vuedraggable";
    import SettingsModal from './../SettingsModal.vue';

    const props = defineProps({
        elementData: Object,
    });

    const showModal = ref(false);
</script>


<template>
    <div class="widget" :id="props.elementData.uid">
        <div :class="['widget__header', 'widget__header--grey', props.elementData.color]">
            <div class="widget__actions">
                <i class="bi bi-gear-fill open" @click="(e) => {showModal = true}"></i>
                <i class="bi bi-copy pointer" @click="contentStore.findAndDuplicateByUID(props.elementData.uid)"></i>
                <i class="bi bi-trash3 close" @click="contentStore.removeElement(props.elementData.uid)"></i>
            </div>            
            <div class="widget__title"><i :class="props.elementData.icon"></i> {{ props.elementData.name }} {{ (props.elementData.settings.title) ? "("+props.elementData.settings.title+")" : "" }}</div>
            <div class="widget__info"></div>
        </div>

        <Teleport to="body">
            <SettingsModal :elementData="props.elementData" :show="showModal" @update="(e) => {showModal = false}">
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
    import { ref, watch } from 'vue';
    import { contentStore } from './../store/content.js'
    import SettingsModal from './../SettingsModal.vue';

    const props = defineProps({
        elementData: Object,
        parent: {
            type: Object,
            default: null
        }        
    });

    const showModal = ref(false);    
</script>

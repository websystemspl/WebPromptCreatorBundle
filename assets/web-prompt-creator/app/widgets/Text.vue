<template>
    <div class="widget" :id="props.elementData.uid">
        <div :class="['widget__header', 'widget__header--grey', props.elementData.color]">
            <div class="widget__actions">
                <i class="bi bi-gear-fill open" @click="(e) => {showModal = true}"></i>
                <i class="bi bi-copy pointer" @click="contentStore.findAndDuplicateByUID(props.elementData.uid)"></i>
                <i class="bi bi-trash3 close" @click="contentStore.removeElement(props.elementData.uid)"></i>
            </div>            
            <div class="widget__title"><i :class="props.elementData.icon"></i> {{ props.elementData.name }} {{ (props.elementData.settings.title) ? "("+props.elementData.settings.title+")" : "" }}</div>
            <div class="widget__info">
                <span v-if="props.parent === null" class="wpc-badge wpc-badge--primary">{{ props.elementData.settings.role }}</span>
                <button class="button button--empty" type="button" @click="props.elementData.openToggle = !props.elementData.openToggle">
                    <i :class="(props.elementData.openToggle) ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                </button>
            </div>
        </div>
        <div class="widget__body" v-if="props.elementData.openToggle">
            <div class="wpc-form wpc-form--full-width">
                <div class="wpc-form__field">
                    <div class="wpc-form__input">
                        <textarea rows="2" v-model="props.elementData.settings.content" @input="(e) => { props.elementData.settings.content = e.target.value }"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <Teleport to="body">
            <SettingsModal :elementData="props.elementData" :modalTitle="props.elementData.name" :show="showModal" @update="(e) => {showModal = false}">
                <p>UID: #{{ props.elementData.uid }}</p>
                <div class="wpc-form wpc-form--full-width">
                    <div v-if="props.parent !== null" class="wpc-form__input-group">
                        <div class="wpc-form__field">
                            <label for="" class="wpc-form__label">New lines before</label>
                            <div class="wpc-form__input">
                                <input type="number" v-model="props.elementData.settings.new_lines_before" @input="(e) => { props.elementData.settings.new_lines_before = e.target.value }" />
                            </div>
                        </div>
                        <div class="wpc-form__field">
                            <label for="" class="wpc-form__label">New lines after</label>
                            <div class="wpc-form__input">
                                <input type="number" v-model="props.elementData.settings.new_lines_after" @input="(e) => { props.elementData.settings.new_lines_after = e.target.value }" />
                            </div>
                        </div>
                    </div>
                    <div class="wpc-form__field">
                        <label for="" class="wpc-form__label">Title</label>
                        <div class="wpc-form__input">
                            <input type="text" v-model="props.elementData.settings.title" @input="(e) => { props.elementData.settings.title = e.target.value }" />
                        </div>
                    </div>
                    <div v-if="props.parent === null" class="wpc-form__field">
                        <label for="" class="wpc-form__label">Role</label>
                        <div class="wpc-form__input">
                            <select v-model="props.elementData.settings.role" @change="(e) => { props.elementData.settings.role = e.target.value }">
                                <option value="user">User</option>
                                <option value="assistant">Assistant</option>
                                <option value="system">System</option>
                            </select>
                        </div>
                    </div>
                    <div class="wpc-form__field">
                        <label for="" class="wpc-form__label">Text content</label>
                        <div class="wpc-form__input">
                            <textarea rows="5" v-model="props.elementData.settings.content" @input="(e) => { props.elementData.settings.content = e.target.value }"></textarea>
                        </div>
                    </div>
                </div>
            </SettingsModal>
        </Teleport>        
    </div>
</template>

<script setup>
    import { ref, watch, computed } from 'vue';
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

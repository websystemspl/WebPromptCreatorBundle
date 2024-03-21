<style type="text/css">
	.st0{fill:#FFFFFF;}
</style>

<template>
    <div class="pb-layout" ref="pbLayout">

        <div class="pb-layout__header">
            <div class="pb-logo">
                <svg version="1.1" id="Warstwa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 194 135.8" style="enable-background:new 0 0 194 135.8;" xml:space="preserve">
                    <path style="fill:#FFFFFF;" d="M174.6,32.33V19.4c0-10.71-8.69-19.4-19.4-19.4h-25.87c-10.71,0-19.4,8.69-19.4,19.4v12.93H84.07
                        V19.4C84.07,8.69,75.38,0,64.67,0H38.8C28.09,0,19.4,8.69,19.4,19.4v12.93C8.69,32.33,0,41.02,0,51.73v64.67
                        c0,10.71,8.69,19.4,19.4,19.4h155.2c10.71,0,19.4-8.69,19.4-19.4V51.73C194,41.02,185.31,32.33,174.6,32.33z M109.17,119.79
                        L109.17,119.79c-2.39,0-4.34-1.96-4.34-4.34c0-2.39,1.96-4.34,4.34-4.34h0c2.39,0,4.34,1.96,4.34,4.34
                        C113.51,117.83,111.56,119.79,109.17,119.79z M174.82,119.79h-51.85c-2.39,0-4.34-1.96-4.34-4.34c0-2.39,1.96-4.34,4.34-4.34h51.85
                        c2.39,0,4.34,1.96,4.34,4.34C179.16,117.83,177.21,119.79,174.82,119.79z"/>
                </svg>
                <div>
                    <h2>WPC</h2>
                    <p>Web Prompt Creator</p>
                </div>
            </div>
            <div class="pb-layout__header-actions">
                <i class="bi bi-gear-fill open" @click="(e) => {showModal = true}"></i>
                <i class="bi bi-filetype-json open" @click="(e) => {showModalCode = true}"></i>
                <i class="bi bi-view-list open" @click="(e) => {showModalConversation = true}"></i>
                <span @click="fullScreenToggle()">
                    <i v-if="isFullScreen" class="bi bi-fullscreen-exit"></i>
                    <i v-if="!isFullScreen" class="bi bi-fullscreen"></i>
                </span>
            </div>
        </div>
        <div class="pb-layout__body">
            <div class="pb-layout__col pb-layout__col--left">
                <div class="pb-widgets">
                    <div class="pb-widgets__header">Containers</div>
                    <draggable 
                        class="list-group" 
                        :list="widgets.containers"
                        :clone="cloneElement"
                        itemKey="id"
                        :group="{ name: 'containers', pull: 'clone', put: false }"
                        :sort="false"
                    >
                        <template #item="{ element }">
                            <div class="pb-widget widget-item">
                                <i :class="element.icon"></i> {{ element.name }}
                            </div>
                        </template>
                    </draggable>
                    <div class="pb-widgets__header">Widgets</div>
                    <draggable 
                        class="list-group" 
                        :list="widgets.widgets"
                        :clone="cloneElement"
                        itemKey="id"
                        :group="{ name: 'widgets', pull: 'clone', put: false }"
                        :sort="false"
                    >
                        <template #item="{ element }">
                            <div class="pb-widget widget-item">
                                <i :class="element.icon"></i> {{ element.name }}
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>
            <div class="pb-layout__col pb-layout__col--right">
                <draggable
                    class="dragArea widget__dragArea"
                    :list="contentStore.content"
                    group="containers"
                    itemKey="id"
                >
                    <template #item="{ element, index }">
                        <div class="">
                            <component :elementData="element" :is="element.component.object"></component>    
                        </div>
                    </template>
                </draggable>      
            </div>
        </div>
        <Teleport to="body">
            <SettingsModal :modalTitle="'Global settings'" :show="showModal" @update="(e) => {showModal = false}">
                <div class="wpc-form wpc-form--full-width">
                    <div class="wpc-form__field">
                        <label for="" class="wpc-form__label">Export template</label>
                        <div class="wpc-form__input">
                            <button type="button" class="btn btn-primary" @click="exportJson()"><i class="bi bi-download"></i></button>
                        </div>
                    </div>             
                    <div class="wpc-form__field">
                        <label for="" class="wpc-form__label">Import template</label>
                        <div class="wpc-form__input">
                            <button type="button" class="btn btn-primary" @click="importJson()"><i class="bi bi-upload"></i></button>
                        </div>
                    </div>                       
                </div>
            </SettingsModal>
        </Teleport> 
        <Teleport to="body">
            <SettingsModal :width="1000" :modalTitle="'JSON Code'" :show="showModalCode" @update="(e) => {showModalCode = false}">
                <div class="wpc-form wpc-form--full-width">
                    <div class="wpc-form__field">
                        <label for="" class="wpc-form__label">JSON code</label>
                        <div class="wpc-form__input">
                            <textarea rows="20" :value="JSON.stringify(contentStore.content,null,4)"></textarea>
                        </div>
                    </div>             
                </div>
            </SettingsModal>
        </Teleport>         
        <Teleport to="body">
            <SettingsModal :width="1000" :modalTitle="'Conversation view'" :show="showModalConversation" @update="(e) => {showModalConversation = false}">
                <div class="settings-modal__scrollable">
                    <div class="wpc-conversation">
                        <div class="wpc-conversation__request" v-for="request in contentStore.content">
                            <div class="wpc-conversation__request-title">#{{ request.uid }} {{ (request.settings.title) ? " - "+request.settings.title : '' }}</div>
                            <div class="wpc-conversation__widget" v-for="widget in request.widgets">
                                <div class="wpc-conversation__role"><span>Role:</span> {{ widget.settings.role }}</div>
                                <div v-if="!widget.settings.widgets">
                                    <div class="wpc-conversation__content" v-if="widget.settings.content"><span>Content:</span> {{ widget.settings.content }}</div>
                                    <div class="wpc-conversation__content" v-if="widget.settings.relation"><span>Content:</span> [RESPONSE OF RELATION - {{ widget.settings.relation }}]</div>
                                    <div class="wpc-conversation__content" v-if="widget.settings.input"><span>Content:</span> [INPUT DATA - {{ widget.settings.input }} ]</div>
                                </div>
                                <div v-if="widget.widgets">
                                    <ConversationWidgets :widgets="widget.widgets" />
                                </div>
                            </div>
                            <div class="wpc-conversation__response">[RESPONSE OF REQUEST]</div>
                        </div>            
                    </div>
                </div>
            </SettingsModal>
        </Teleport>         
    </div>
</template>

<script setup>
    import { ref, defineAsyncComponent, markRaw } from 'vue';
    import { contentStore } from './store/content.js'
    import draggable from "vuedraggable";
    import {default as download} from 'downloadjs';
    import SettingsModal from './SettingsModal.vue';
    import ConversationWidgets from './ConversationWidgets.vue';
    import widgets from "./widgets/widgets.js"

    const props = defineProps({
        content: String
    });

    const showModal = ref(false);   
    const showModalCode = ref(false);
    const showModalConversation = ref(false);
    const pbLayout = ref(null);
    const isFullScreen = ref(false);

    function exportJson()
    {
        const datetime = new Date().toISOString().replace(/[-:]/g, '_').replace('T', '_').split('.')[0];
        download(JSON.stringify(contentStore.content), 'template_' + datetime + '.json', 'text/plain')
    }

    function importJson()
    {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.json';
        input.onchange = e => { 
            const file = e.target.files[0]; 
            const reader = new FileReader();
            reader.onload = function(e) {
                contentStore.content = contentStore.recursiveReplaceComponent(JSON.parse(e.target.result));
            }
            reader.readAsText(file);
        }
        input.click();
    }

    function cloneElement({ id, type })
    {
        let found = null;
        if(type === 'container') {
            found = widgets.containers.find((element) => element.id === id);
        }
        if(type === 'widget') {
            found = widgets.widgets.find((element) => element.id === id);
        }
        let elem = JSON.parse(JSON.stringify(found));
        const component = markRaw(defineAsyncComponent(() => {
          return import(`./widgets/${found.component.name}.vue`);
        }));
        elem.component.object = component;
        elem.uid = contentStore.generateUid();
        return elem;
    }

    function fullScreenToggle()
    {
        if(isFullScreen.value) {
            pbLayout.value.classList.toggle('pb-layout--fullscreen');
            isFullScreen.value = false;
        } else {
            pbLayout.value.classList.toggle('pb-layout--fullscreen');
            isFullScreen.value = true;
        }
    }
</script>

<template>
    <draggable 
        class="list-group" 
        :list="props.widgets"
        itemKey="uid"
        :group="{ name: 'widgets', pull: true, put: true }"
        :sort="true"
    >
        <template #item="{ element }">
            <div :style="'margin-left:'+props.indent">
                <div :class="['pb-layer', 'layer-item', element.color]">
                    <div class="wpc-layer">
                        <div class="wpc-layer__left">
                            <i :class="element.icon"></i> {{ element.name }} {{ element.settings.title }} 
                        </div>
                        <div class="wpc-layer__right">
                            <span @click="scrollTo(element.uid)">#{{ element.uid }}</span>
                            <button class="button button--empty" type="button" @click="element.openToggle = !element.openToggle">
                                <i :class="(element.openToggle) ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                            </button>                              
                        </div>
                    </div>
                </div>
                <Transition>
                    <Layers :indent="'20px'" :widgets="element.widgets" v-if="element.openToggle" />
                </Transition>
            </div>
        </template>
    </draggable>
</template>

<script setup>
    import draggable from "vuedraggable";
    const props = defineProps({
        widgets: Array,
        indent: {
            type: String,
            default: '0px'
        }
    });

    function scrollTo(uid)
    {
        const el = document.getElementById(uid);
        el.scrollIntoView({behavior: "smooth", block: "start"});
    }    
</script>
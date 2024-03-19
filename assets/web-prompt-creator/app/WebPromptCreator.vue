<template>
    <textarea v-if="!props.element_hidden" v-model="element" :id="props.element_id" :name="props.element_name" rows="3" :class="props.element_class"></textarea>
    <input type="hidden" v-if="props.element_hidden" v-model="element" :id="props.element_id" :name="props.element_name" :class="props.element_class" />
    <Layout :content="props.element_value" />
    <div class="minimized-area">
    </div>
</template>

<script setup>
    import { computed, onMounted } from 'vue';
    import Layout from './Layout.vue';
    import { contentStore } from './store/content.js'
    import { inputStore } from './store/input.js'
    
    const props = defineProps({
        element_hidden: {
            type: Boolean,
            default: false
        },
        element_id: String,
        element_name: String,
        element_value: String,
        element_class: {
            type: String,
            default: 'form-control'
        },
        input: Object
    });

    const element = computed(() => {
        return JSON.stringify(contentStore.content)
    })

    onMounted(() => {
        contentStore.content = contentStore.recursiveReplaceComponent(JSON.parse(
            (props.element_value) ? props.element_value : '[]'
        ));

        inputStore.input = props.input;
    })
    element.name = props.element_name;
</script>

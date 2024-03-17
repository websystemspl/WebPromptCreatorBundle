<template>
        <div class="wpc-conversation__content"><span>Content:</span> {{ concatenateWidgets() }}</div>
        <div v-for="widget in widgets">
            <ConversationWidgets v-if="widget.widgets" :widgets="widget.widgets" />
        </div>
</template>

<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        widgets: Object
    });

    function concatenateWidgets()
    {
        let content = "";

        props.widgets.forEach((widget) => {
            if (widget.settings.content) {
                content += widget.settings.content;
            }

            if (widget.settings.relation) {
                content += "[RELATION - " + widget.settings.relation + "]";
            }
            if (widget.settings.input) {
                content += "[INPUT - " + widget.settings.input + "]";
            }

        });

        return content;
    }
</script>
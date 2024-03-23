<template>
        <div class="wpc-conversation__content"><span class="wpc-conversation__role">Content:</span> <span v-html="concatenateWidgets()"></span></div>
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
                if(widget.settings.new_lines_before)
                {
                    for (let i = 0; i < widget.settings.new_lines_before; i++) {
                        content += "<br>";
                    }
                }
                content += nl2br(widget.settings.content);
                if(widget.settings.new_lines_after)
                {
                    for (let i = 0; i < widget.settings.new_lines_after; i++) {
                        content += "<br>";
                    }
                }
            }

            if (widget.settings.relation) {
                if(widget.settings.new_lines_before)
                {
                    for (let i = 0; i < widget.settings.new_lines_before; i++) {
                        content += "<br>";
                    }
                }                
                content += "[RELATION - " + widget.settings.relation + "]";
                if(widget.settings.new_lines_after)
                {
                    for (let i = 0; i < widget.settings.new_lines_after; i++) {
                        content += "<br>";
                    }
                }                
            }
            if (widget.settings.input) {
                if(widget.settings.new_lines_before)
                {
                    for (let i = 0; i < widget.settings.new_lines_before; i++) {
                        content += "<br>";
                    }
                } 
                content += "[INPUT - " + widget.settings.input + "]";
                if(widget.settings.new_lines_after)
                {
                    for (let i = 0; i < widget.settings.new_lines_after; i++) {
                        content += "<br>";
                    }
                }
            }

        });

        return content;
    }

    function nl2br(str)
    {
        return str.replace(/(?:\r\n|\r|\n)/g, '<br>');
    }
</script>
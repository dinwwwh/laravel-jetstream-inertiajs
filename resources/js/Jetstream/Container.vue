<template>
    <Component :is="is" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <slot />
    </Component>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'JetContainer',
    props: {
        is: {
            type: String,
            default: 'div',
        },
    },
});
</script>

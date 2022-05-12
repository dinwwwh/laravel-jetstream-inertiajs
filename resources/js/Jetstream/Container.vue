<template>
    <Component :is="is" class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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

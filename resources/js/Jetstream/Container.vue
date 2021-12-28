<template>
    <div :as="as" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <slot />
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'JetContainer',
    props: {
        as: {
            type: String,
            default: 'div',
        },
    },
});
</script>

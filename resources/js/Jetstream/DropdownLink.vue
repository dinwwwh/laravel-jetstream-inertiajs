<template>
    <div>
        <button
            v-if="as == 'button'"
            type="submit"
            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
        >
            <slot></slot>
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-gray-700 transition hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
        >
            <slot></slot>
        </a>

        <Link
            v-else
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-gray-700 transition hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
        >
            <slot></slot>
        </Link>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

export default defineComponent({
    name: 'JetDropdownLink',
    components: {
        Link,
    },
    props: {
        href: {
            type: String,
            default: '#',
        },
        as: {
            type: String,
            default: undefined,
        },
    },
});
</script>

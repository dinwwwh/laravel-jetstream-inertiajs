<template>
    <Head title="API Tokens" />

    <header class="bg-white shadow">
        <JetContainer class="py-6 px-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Tokens
            </h2>
        </JetContainer>
    </header>

    <JetContainer is="main" class="py-12">
        <api-token-manager
            :tokens="tokens"
            :available-permissions="availablePermissions"
            :default-permissions="defaultPermissions"
        />
    </JetContainer>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';

import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue';
import JetContainer from '@/Jetstream/Container.vue';

export default defineComponent({
    name: 'ApiIndexPage',
    components: {
        ApiTokenManager,
        Head,
        JetContainer,
    },

    props: {
        tokens: {
            type: Array as PropType<any>,
            required: true,
        },
        availablePermissions: {
            type: Array as PropType<any>,
            required: true,
        },
        defaultPermissions: {
            type: Array as PropType<any>,
            required: true,
        },
    },
});
</script>

<template>
    <app-layout title="API Tokens">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Tokens
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <api-token-manager
                    :tokens="tokens"
                    :available-permissions="availablePermissions"
                    :default-permissions="defaultPermissions"
                />
            </div>
        </div>
    </app-layout>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

export default defineComponent({
    name: 'ApiIndexPage',
    components: {
        ApiTokenManager,
        AppLayout,
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

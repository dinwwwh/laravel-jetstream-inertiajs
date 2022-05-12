<template>
    <!-- eslint-disable vue/no-v-html -->
    <Head title="Privacy Policy" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="bg-gray-100 pt-4">
            <div class="flex min-h-screen flex-col items-center pt-6 sm:pt-0">
                <div>
                    <jet-authentication-card-logo />
                </div>

                <div
                    class="prose mt-6 w-full overflow-hidden bg-white p-6 shadow-md sm:max-w-2xl sm:rounded-lg"
                    v-html="policy"
                ></div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

export default defineComponent({
    name: 'PrivacyPolicyPage',
    components: {
        Head,
        JetAuthenticationCardLogo,
    },
    layout: null,

    props: {
        policy: {
            type: String,
            required: true,
        },
    },
});
</script>

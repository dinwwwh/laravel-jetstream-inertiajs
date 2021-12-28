<template>
    <Head title="Dashboard" />

    <header class="bg-white shadow">
        <JetContainer class="py-6 px-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </JetContainer>
    </header>

    <JetContainer is="main" class="py-12">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <welcome />
        </div>
    </JetContainer>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';

import Welcome from '@/Jetstream/Welcome.vue';
import JetContainer from '@/Jetstream/Container.vue';

export default defineComponent({
    name: 'DashboardPage',
    components: {
        Welcome,
        JetContainer,
        Head,
    },
});
</script>

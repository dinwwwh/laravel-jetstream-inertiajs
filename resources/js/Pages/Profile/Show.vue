<template>
    <Head title="Profile" />

    <header class="bg-white shadow">
        <JetContainer class="py-6 px-4">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Profile
            </h2>
        </JetContainer>
    </header>

    <JetContainer is="main" class="py-12">
        <div v-if="($page as any).props.jetstream.canUpdateProfileInformation">
            <update-profile-information-form
                :user="($page as any).props.user"
            />

            <jet-section-border />
        </div>

        <div v-if="($page as any).props.jetstream.canUpdatePassword">
            <update-password-form class="mt-10 sm:mt-0" />

            <jet-section-border />
        </div>

        <div
            v-if="
                        ($page as any).props.jetstream.canManageTwoFactorAuthentication
                    "
        >
            <two-factor-authentication-form class="mt-10 sm:mt-0" />

            <jet-section-border />
        </div>

        <logout-other-browser-sessions-form
            :sessions="sessions"
            class="mt-10 sm:mt-0"
        />

        <template
            v-if="($page as any).props.jetstream.hasAccountDeletionFeatures"
        >
            <jet-section-border />

            <delete-user-form class="mt-10 sm:mt-0" />
        </template>
    </JetContainer>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';

import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import JetSectionBorder from '@/Jetstream/SectionBorder.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import JetContainer from '@/Jetstream/Container.vue';

export default defineComponent({
    name: 'ProfileShowPage',
    components: {
        DeleteUserForm,
        JetSectionBorder,
        LogoutOtherBrowserSessionsForm,
        TwoFactorAuthenticationForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
        Head,
        JetContainer,
    },

    props: {
        sessions: {
            type: Array as PropType<any>,
            required: true,
        },
    },
});
</script>

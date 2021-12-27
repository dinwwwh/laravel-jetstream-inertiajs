<template>
    <span>
        <span @click="startConfirmingPassword">
            <slot />
        </span>

        <jet-dialog-modal :show="confirmingPassword" @close="closeModal">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                {{ content }}

                <div class="mt-4">
                    <jet-input
                        ref="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="confirmPassword"
                    />

                    <jet-input-error :message="form.error" class="mt-2" />
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click="closeModal">
                    Cancel
                </jet-secondary-button>

                <jet-button
                    class="ml-2"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="confirmPassword"
                >
                    {{ button }}
                </jet-button>
            </template>
        </jet-dialog-modal>
    </span>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import JetButton from './Button.vue';
import JetDialogModal from './DialogModal.vue';
import JetInput from './Input.vue';
import JetInputError from './InputError.vue';
import JetSecondaryButton from './SecondaryButton.vue';

export default defineComponent({
    name: 'JetConfirmsPassword',
    components: {
        JetButton,
        JetDialogModal,
        JetInput,
        JetInputError,
        JetSecondaryButton,
    },

    props: {
        title: {
            type: String,
            default: 'Confirm Password',
        },
        content: {
            type: String,
            default:
                'For your security, please confirm your password to continue.',
        },
        button: {
            type: String,
            default: 'Confirm',
        },
    },

    emits: ['confirmed'],

    data() {
        return {
            confirmingPassword: false,
            form: {
                password: '',
                error: '',
                processing: false,
            },
        };
    },

    methods: {
        startConfirmingPassword() {
            window.axios
                .get(window.route('password.confirmation'))
                .then((response) => {
                    if (response.data.confirmed) {
                        this.$emit('confirmed');
                    } else {
                        this.confirmingPassword = true;

                        setTimeout(
                            () => (this.$refs.password as any).focus(),
                            250
                        );
                    }
                });
        },

        confirmPassword() {
            this.form.processing = true;

            window.axios
                .post(window.route('password.confirm'), {
                    password: this.form.password,
                })
                .then(() => {
                    this.form.processing = false;
                    this.closeModal();
                    this.$nextTick(() => this.$emit('confirmed'));
                })
                .catch(({ response }) => {
                    const [error] = response.data.errors.password;
                    this.form.error = error;
                    this.form.processing = false;
                    (this.$refs.password as any).focus();
                });
        },

        closeModal() {
            this.confirmingPassword = false;
            this.form.password = '';
            this.form.error = '';
        },
    },
});
</script>

<template>
    <input
        ref="input"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        :value="modelValue"
        @input="update"
    />
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'JetInput',
    props: {
        modelValue: {
            type: null,
            default: '',
        },
    },

    emits: ['update:modelValue'],

    methods: {
        focus() {
            (this.$refs.input as HTMLInputElement).focus();
        },
        update(e: Event) {
            this.$emit(
                'update:modelValue',
                (e.target as HTMLInputElement).value
            );
        },
    },
});
</script>

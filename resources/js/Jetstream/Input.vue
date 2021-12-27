<template>
    <input
        ref="input"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
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

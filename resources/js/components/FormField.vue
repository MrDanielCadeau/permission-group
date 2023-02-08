<template>
    <div v-if="children?.length">
        <div class="px-6 md:px-8 mt-2 md:mt-0 w-full md:w-1/5 md:py-5 flex justify-start w-full">
            <h3 class="w-fit text-80 font-bold">{{ name }}</h3>
        </div>

        <ul class="permissions__grid__form">
            <li v-for="(child, index) in children"
                :key="index">
                <component
                    :is="`form-${child.component}`"
                    :field="child"
                    :show-help-text=true
                    @change="syncValue($event)"
                />
            </li>
        </ul>
    </div>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import {formatChildren} from "../formatChildren"

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ["field"],
    data: () => ({
        value: undefined,
        children: [],
    }),

    watch: {
        value: {
            handler(newValue) {
                if(newValue!== undefined) {
                    return this.children = formatChildren(newValue, this.field.children)
                }
            },
            immediate: true
        },
    },

    computed: {
        name() {
            return this.field.name
        },
    },

    methods: {
        syncValue(e) {
            const checkbox = e.target?.attributes?.name?.value
            const arr = this.value?.length < 1 ? [] : JSON.parse(this.value)
            const i = arr.indexOf(checkbox);
            if (i > -1) {
                arr.splice(i, 1)
            } else {
                arr.push(checkbox)
            }
            this.value = JSON.stringify(arr)
        },
    },
};
</script>

<style>
.permissions__grid__form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    grid-gap: 16px;
}
</style>

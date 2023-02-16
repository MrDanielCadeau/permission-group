<template>
    <div>
        <div class="mt-2 md:mt-0 w-full md:w-1/5 md:py-5 flex justify-start w-full">
            <h3 class="w-fit text-80 font-bold">{{ name }}</h3>
        </div>

        <ul class="permissions__grid__detail">
            <li v-for="(child, index) in children"
                :key="index">
                <component
                    :is="`detail-${child.component}`"
                    :field="child"
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
        values: [],
        children: [],
    }),

    watch: {
        value: {
            handler(newValue) {
                if (newValue !== undefined) {
                    this.setValues(newValue)
                    this.setChildren()
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
        setValues(v) {
            this.values = (v !== '' ? JSON.parse(v) : [])
        },

        setChildren() {
            const temp_children = this.children.length > 0 ? this.children : this.field.children
            this.children = formatChildren(this.values, temp_children, false)
        },
    },
};
</script>

<style>
.permissions__grid__detail {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    grid-gap: 16px;
}
</style>

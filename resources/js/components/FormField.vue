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
                    :key="child.value"
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
import {getSelectedValues} from "../getSelectedValues"

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
            this.children = formatChildren(this.values, temp_children)
        },

        syncValue(e) {
            const checkbox = e.target?.attributes?.name?.value
            if (checkbox.includes('select_all')) return this.addGroupToValue(checkbox)
            else return this.addOneToValue(checkbox)
        },

        addOneToValue(v) {
            const arr = this.values
            const i = arr.indexOf(v)
            if (i > -1) {
                arr.splice(i, 1)
            } else {
                arr.push(v)
            }
            this.value = JSON.stringify(arr)
        },

        forceAdd(v) {
            const arr = this.values
            arr.push(v)
            this.value = JSON.stringify(arr)
        },

        forceRemove(v) {
            const arr = this.values
            const i = arr.indexOf(v)
            if (i > -1) {
                arr.splice(i, 1)
            }
            this.value = JSON.stringify(arr)
        },

        addGroupToValue(v) {
            const groupName = v.replace('select_all_', '')
            const group = this.children.filter(child => child.attribute === groupName)[0]
            let selectedValues = getSelectedValues(this.values, groupName)
            let selectedAll = (selectedValues.length === group.options.length - 1)

            group.options.forEach(option => {
                if (!option.name.includes('select_all')) {
                    let value = option.name
                    this.forceRemove(value)
                    if(!selectedAll) this.forceAdd(value)
                }
            })
        },
    },
}
</script>

<style>
.permissions__grid__form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    grid-gap: 16px;
}

input[type='checkbox'] {
    margin-right: 0.5rem!important;
}
</style>

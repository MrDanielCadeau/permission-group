<template>
    <div v-if="value">
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

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ["field"],
    data: () => ({
        value: '',
        children: [],
    }),
    watch: {
        value: {
            handler(newValue) {
                if(newValue?.length) {
                    let children = this.field.children

                    let values = (this.value !== '' ? JSON.parse(this.value) : null)
                    let formatValues = {}
                    if (values) {
                        values.forEach(value => {
                            formatValues[value] = true
                        })
                    }

                    return this.children = children.map(child => {
                        child.value = formatValues
                        return child
                    })
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
};
</script>

<style>
.permissions__grid__detail {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    grid-gap: 16px;
}
</style>

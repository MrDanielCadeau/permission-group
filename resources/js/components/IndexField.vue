<template>
    <ul v-if="value" class="permissions__grid__index">
        <li v-for="(child, index) in children"
            :key="index">
            <p>{{ child.name }}</p>
            <component
                :is="`index-${child.component}`"
                :field="child"
            />
        </li>
    </ul>
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
.permissions__grid__index {
    display: grid;
    grid-gap: 4px;
}

@media (min-width: 1024px) {
    .permissions__grid__index {
        grid-template-columns: repeat(4, minmax(0, 1fr))!important;
    }
}

@media (min-width: 640px) {
    .permissions__grid__index {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

</style>

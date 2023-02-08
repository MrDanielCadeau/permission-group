<template>
    <ul class="permissions__grid__index">
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
            handler: function(newValue) {
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
};
</script>

<style>
.permissions__grid__index {
    display: grid;
    grid-gap: 4px;
}

@media (min-width: 1024px) {
    .permissions__grid__index {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }
}

@media (min-width: 640px) {
    .permissions__grid__index {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

</style>

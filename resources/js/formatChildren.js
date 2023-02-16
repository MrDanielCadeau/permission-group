import {getSelectedValues} from "./getSelectedValues"

export function formatChildren(values, children) {
    return children.map(child => {
        let formatValues = {}
        let selectedValues = getSelectedValues(values, child.attribute)

        let select_all_input = `select_all_${child.attribute}`

        selectedValues.forEach(v => {
            formatValues[v] = true
        })

        formatValues[select_all_input] = (
            child.options.length === selectedValues.length
            || child.options.length === selectedValues.length + 1
        )

        if (child.options.filter(option => option.name === select_all_input).length === 0) {
            child.options.unshift({
                label: 'Select all',
                name: select_all_input,
            })
        }

        child.value = formatValues
        return child
    })
}

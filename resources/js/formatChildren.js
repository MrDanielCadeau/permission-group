export function formatChildren (value, children) {
    let values = (value !== '' ? JSON.parse(value) : [])
    let formatValues = {}

    values.forEach(value => {
        formatValues[value] = true
    })

    return children.map(child => {
        child.value = formatValues
        return child
    })
}

export function getSelectedValues(values, needle) {
    return values.filter(v => v.includes(needle) && !v.includes(needle + '_'))
}

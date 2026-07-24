function toggleParentChoice(checked){
console.log(checked)
    if (checked){
        document.querySelector('.parent_choice').classList.add('d-none')
        document.getElementById('Category_parent_autocomplete').tomselect.clear()
    }else{
        document.querySelector('.parent_choice').classList.remove('d-none')

    }
}

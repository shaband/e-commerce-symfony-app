// htmx.on("htmx:load", function(evt) {
//     myJavascriptLib.init(evt.detail.elt);
// });
htmx.logAll();

function toggleDependentSwitch(val) {


    let ChildrenCategorySwitch = document.querySelector('.showChildrenInHome')
    let productSwitch = document.querySelector('.showProductsInHome')
    let parentSelect = document.querySelector('.parent_category')

    if (val === 'Main') {
        ChildrenCategorySwitch.classList.remove('d-none')
        productSwitch.classList.remove('d-none')
        parentSelect.classList.add('d-none')
    } else if (val === 'Group') {
        ChildrenCategorySwitch.classList.remove('d-none')
        productSwitch.classList.remove('d-none')
        parentSelect.classList.remove('d-none')
    }
    else if (val === 'Item') {
        ChildrenCategorySwitch.classList.add('d-none')
        productSwitch.classList.remove('d-none')
        document.getElementById('Category_showProductsInHome').checked=false;
        parentSelect.classList.remove('d-none')
    }else{
        ChildrenCategorySwitch.classList.add('d-none')
        productSwitch.classList.add('d-none')
        parentSelect.classList.add('d-none')
    }

}
window.addEventListener('load',function (){

let category_group_choice=document.getElementById("Category_category_group")
    toggleDependentSwitch(category_group_choice.value)

})


import '../styles/tasks.scss';
//nie jestem pewien czy jest potrzebne
/* document.querySelector('.confirm-delete').addEventListener('click', event => {
    return confirm('Are you sure to delete?');
}); */

//zmienną deklarujemy jako window.variableName

// info ukrywamy pole checkbox +Opis dla zaawansowanej wyszukiwarki
// question - czy tutaj zasaday SOLID zostałą złamana? jedna funkcja chowa i ukrywa? Zrobić dwie funkcje?
function toggleAddDescriptionBtn(hideOrShow){

    const btn_wrapper = document.getElementById('addDescriptionWrapper')
    btn_wrapper.style.display = hideOrShow
    btn_wrapper.querySelector('input[type="checkbox"]').checked = false
}

const myCollapsible = document.getElementById('collapseAdvancedSearch')

myCollapsible.addEventListener('hide.bs.collapse', event => {
    toggleAddDescriptionBtn('flex')
})

myCollapsible.addEventListener('show.bs.collapse', event => {
    toggleAddDescriptionBtn('none')
})


// info wysylamy grupowe akcje z liosty zadań
function doGroupAction(event, type_action) {

    const task_checkboxes = document.querySelectorAll('input[name="task[]"]:checked')

    if (task_checkboxes.length === 0) {
        alert('Zaznacz przynajmniej jedno zadanie')
        event.preventDefault()
    } else {
        document.getElementById('group_action_name').value = type_action
    }
}

document.getElementById('btn_group_action_delete').addEventListener('click', function(event) {
    doGroupAction(event, 'delete')
});
document.getElementById('btn_group_action_done').addEventListener('click', event => {
    doGroupAction(event, 'done')
});
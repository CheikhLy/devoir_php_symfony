document.addEventListener('DOMContentLoaded', function () {
    const checkAddUser = document.querySelector('#client_addUser');
    
    const userForm = document.querySelector('#client_users');
    userForm.classList.add('hidden');
    showFormUser(checkAddUser);

    checkAddUser.addEventListener('change', (e) => {
        
        showFormUser(checkAddUser);

    })

    
    function showFormUser(checkbox){
        if (checkbox.checked) {
            userForm.classList.remove('hidden');
        }else{
            userForm.classList.add('hidden');
        }
    }
    
})
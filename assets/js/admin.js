document.addEventListener("DOMContentLoaded", function () {
    const roleSelects = document.querySelectorAll('select');
    roleSelects.forEach(select => {
        const originalRole = select.getAttribute('data-original-role');
        
        select.addEventListener('change', (event) => {
            const selectedSelect = event.target; // Get the specific select that triggered the change
            const validateLink = document.querySelector(`.validate-user-icon[data-user-id="${selectedSelect.dataset.userId}"]`);
            const roleValInput = validateLink.querySelector('input[name="roleVal"]');
            const cancelLink = document.querySelector(`.cancel-user-icon[data-user-id="${selectedSelect.dataset.userId}"]`);

            
            if (selectedSelect.value !== originalRole) {
                validateLink.style.display = 'inline';
            } else {
                validateLink.style.display = 'none';
            }

            const selectedRole = selectedSelect.value;
            
            // Set the selected role value in the roleVal input field
            roleValInput.value = selectedRole;

            console.log(roleValInput);

        });
    });
    
    const editLinks = document.querySelectorAll('.edit-user-icon');
    editLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const userId = link.getAttribute('data-user-id');
            const roleSelect = document.getElementById(`user-role-${userId}`);
            roleSelect.removeAttribute('disabled');

            const validateLink = document.querySelector(`.validate-user-icon[data-user-id="${userId}"]`);
            const cancelLink = document.querySelector(`.cancel-user-icon[data-user-id="${userId}"]`);
            roleSelect.dataset.userId = userId; // Store the user ID

            console.log(cancelLink);

            cancelLink.style.display = 'inline';

            cancelLink.addEventListener('click', (event) => {
                event.preventDefault();

                // Reset the select to its original value
                roleSelect.value = roleSelect.getAttribute('data-original-role');
                
                // Disable the select element
                roleSelect.disabled = true;

                validateLink.style.display = 'none';
                cancelLink.style.display = 'none';
            });
        });
    });
  });
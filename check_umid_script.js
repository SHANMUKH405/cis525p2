// JavaScript to check if the UMID already exists and show a warning if updating
function checkForUpdate() {
    const umidField = document.getElementById('umid');
    umidField.addEventListener('blur', function() {
        if (umidField.value) {
            fetch(`check_umid.php?umid=${umidField.value}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert('Warning: You are updating the time slot for an existing registration with the same UMID.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
}

window.onload = checkForUpdate;

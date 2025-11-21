function registerPopup(){
    let registerCard = document.getElementById('cardregister');
    registerCard.removeAttribute('style');

    registerCard.addEventListener('click', function(event) {
        if (event.target === registerCard) {
            registerCard.style.display = 'none'; 
        }
    });
}



document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('em-registration-form');
    const messageDiv = document.getElementById('em-message');

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // 1. Czyszczenie wiadomości
            messageDiv.innerHTML = '';
            
            // 2. Zbierz dane z formularza
            const formData = new FormData(form);

            // 3. Dodaj akcję AJAX i Nonce
            // Zmienna globalna em_ajax_object jest dodawana przez wp_localize_script
            formData.append('action', 'em_register_event');
            formData.append('nonce', em_ajax_object.nonce); 
            
            // Konwersja FormData na format URL-encoded (wymagany przez WordPress AJAX)
            const params = new URLSearchParams(formData).toString();

            try {
                // 4. Wyślij żądanie Fetch API
                const response = await fetch(em_ajax_object.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params
                });

                const data = await response.json();

                // 5. Obsługa odpowiedzi
                if (data.success) {
                    messageDiv.innerHTML = `<p class="success">${data.data.message}</p>`;
                    // Opcjonalnie: zresetuj formularz po sukcesie
                    form.reset(); 
                } else {
                    messageDiv.innerHTML = `<p class="error">${data.data.message}</p>`;
                }

            } catch (error) {
                console.error('Błąd podczas rejestracji:', error);
                messageDiv.innerHTML = '<p class="error">Wystąpił nieznany błąd sieciowy.</p>';
            }
        });
    }

});
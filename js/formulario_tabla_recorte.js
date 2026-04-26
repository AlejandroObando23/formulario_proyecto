document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formCortes');
    const mensajeDiv = document.getElementById('mensaje_respuesta');

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Evita que la página cambie o se recargue

            // Mensaje de carga
            mensajeDiv.style.color = '#f5c518';
            mensajeDiv.innerText = 'Agendando cita...';

            const formData = new FormData(form);

            try {
                // Enviar la petición mediante AJAX (Fetch API)
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                // Convertir la respuesta a JSON
                const data = await response.json();

                if (response.ok) {
                    // Éxito
                    mensajeDiv.style.color = '#4caf50'; // Verde
                    mensajeDiv.innerText = data.message || 'Cita agendada correctamente';
                    form.reset(); // Limpia los campos
                } else {
                    // Error desde el servidor
                    mensajeDiv.style.color = '#f44336'; // Rojo
                    mensajeDiv.innerText = data.error || 'Ocurrió un error al agendar';
                }
            } catch (error) {
                // Error de red
                mensajeDiv.style.color = '#f44336';
                mensajeDiv.innerText = 'Error de conexión con el servidor.';
                console.error('Error:', error);
            }
        });
    }
});

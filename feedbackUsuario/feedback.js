// Sistema de rating com flocos de neve
const allStars = document.querySelectorAll('.bx-snowflake');
const ratingInput = document.getElementById('avaliacaoInput');

// Definir data atual como padrão
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('data_feedback').value = today;
});

allStars.forEach((star, i) => {
    star.onclick = function () {
        const selectedValue = this.getAttribute('data-value');
        
        // Remove active de todos
        allStars.forEach(star => {
            star.classList.remove('active');
        });
        
        // Adiciona active até o clicado
        for (let j = 0; j <= i; j++) {
            allStars[j].classList.add('active');
        }
        
        // Define o valor no input hidden
        ratingInput.value = selectedValue;
    };
});

// Botão cancelar
document.querySelector('.btncancel').addEventListener('click', function() {
    document.querySelector('form').reset();
    allStars.forEach(star => star.classList.remove('active'));
    ratingInput.value = '';
    
    // Resetar data para hoje
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('data_feedback').value = today;
});


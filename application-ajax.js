document.addEventListener('DOMContentLoaded', function () {
    
    const form = document.getElementById('applicationForm');
    const submitBtn = document.getElementById('submitAppBtn');
    const successDiv = document.getElementById('ajaxSuccessMsg');
  
    if (!form) {
    
      return;
    }
  
    form.addEventListener('submit', function (e) {
      e.preventDefault(); 
      
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Пожалуйста, подождите…';
      }
  
     
      const formData = new FormData(form);
  
     
      fetch('submit_application.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())   
        .then(html => {
          
          
          
          if (typeof showMyApplicationsModal === 'function') {
            showMyApplicationsModal();
          }
  
          
          if (successDiv) {
            successDiv.textContent = '✅ Заявление успешно отправлено!';
            successDiv.classList.remove('hidden');
  
            
            setTimeout(function() {
              successDiv.classList.add('hidden');
              successDiv.textContent = '';
            }, 5000);
          }
        })
        .catch(err => {
          
          alert('Ошибка при отправке: ' + err.message);
        })
        .finally(() => {
          
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Отправить заявление';
          }
          
          form.reset();
        });
    });
  });
  
  
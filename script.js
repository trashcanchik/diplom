window.addEventListener('DOMContentLoaded', () => {
  const track = document.querySelector('.carousel-track');
  const items = document.querySelectorAll('.carousel-item');
  const itemWidth = items[0].offsetWidth;
  const visibleItems = 5; 
  let currentIndex = 0;

  function updatePosition() {
    track.style.transition = 'transform 0.5s ease';
    track.style.transform = `translateX(-${itemWidth * currentIndex}px)`;
  }
  function nextSlide() {
    currentIndex++;
    updatePosition();

    if (currentIndex >= items.length - visibleItems) {
      setTimeout(() => {
        track.style.transition = 'none';
        currentIndex = 0;
        updatePosition();
      }, 500);
    }
  }

  function prevSlide() {
    if (currentIndex === 0) {
      track.style.transition = 'none';
      currentIndex = items.length - visibleItems - 1;
      updatePosition();
      setTimeout(() => {
        track.style.transition = 'transform 0.5s ease';
        currentIndex--;
        updatePosition();
      }, 20);
    } else {
      currentIndex--;
      updatePosition();
    }
  }

  document.querySelector('.arrow.right').addEventListener('click', nextSlide);
document.querySelector('.arrow.left').addEventListener('click', prevSlide);
});

function openModal() {
  document.getElementById("modalOverlay").style.display = "block";
  document.getElementById("modalWindow").style.display = "block";
}

function closeModal() {
  document.getElementById("modalOverlay").style.display = "none";
  document.getElementById("modalWindow").style.display = "none";

  // Скрыть все spec-info
  document.querySelectorAll(".spec-info").forEach(el => {
    el.style.display = "none";
  });
}

function toggleSpec(index) {
  const infos = document.querySelectorAll(".spec-info");
  infos.forEach((el, i) => {
    el.style.display = (i === index) ? (el.style.display === "block" ? "none" : "block") : "none";
  });
}

// new function

document.addEventListener('DOMContentLoaded', function(){
  const btn     = document.getElementById('auth-button');
  const modal   = document.getElementById('auth-modal');
  const close   = document.getElementById('auth-close');
  const tabLogin    = document.getElementById('login-tab');
  const tabReg      = document.getElementById('register-tab');
  const formLogin   = document.getElementById('login-form');
  const formRegister= document.getElementById('register-form');
  const switchLinks = document.querySelectorAll('.auth-switch-link');

  function openModal() { modal.classList.add('show'); }
  function closeModal(){ modal.classList.remove('show'); }

  btn.addEventListener('click', openModal);
  close.addEventListener('click', closeModal);
  modal.addEventListener('click', e => {
    if (e.target === modal) closeModal();
  });

  tabLogin.addEventListener('click', () => {
    tabLogin.classList.add('auth-tab--active');
    tabReg.classList.remove('auth-tab--active');
    formLogin.classList.remove('auth-form--hidden');
    formRegister.classList.add('auth-form--hidden');
  });

  tabReg.addEventListener('click', () => {
    tabReg.classList.add('auth-tab--active');
    tabLogin.classList.remove('auth-tab--active');
    formRegister.classList.remove('auth-form--hidden');
    formLogin.classList.add('auth-form--hidden');
  });

  switchLinks.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const tgt = e.target.getAttribute('data-target');
      if (tgt === 'register') tabReg.click();
      if (tgt === 'login')    tabLogin.click();
    });
  });
});

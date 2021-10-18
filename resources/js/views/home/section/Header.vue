<template>
  <header id="header" class="header">
    <nav class="nav container">
      <a href="#" class="nav__logo">
        <img src="/images/logo-amdal-white.png" alt="">
      </a>

      <div id="nav-menu" class="nav__menu">
        <ul class="nav__list">
          <li class="nav__item">
            <a href="#home" class="nav__link active-link">Materi</a>
          </li>
          <li class="nav__item">
            <a href="#about" class="nav__link">Kebijakan</a>
          </li>
          <li class="nav__item">
            <a href="#discover" class="nav__link">Tentang Kami</a>
          </li>
          <li class="nav__item">
            <a href="#place" class="btn__link">Masuk</a>
          </li>
        </ul>

        <i id="nav-close" class="ri-close-line nav__close" />
      </div>

      <div id="nav-toggle" class="nav__toggle">
        <i class="ri-function-line" />
      </div>
    </nav>
  </header>
</template>

<script>
export default {
  name: 'HeaderHome',
  data() {
    const navMenu = document.getElementById('nav-menu');
    const navToggle = document.getElementById('nav-toggle');
    const navClose = document.getElementById('nav-close');
    if (navToggle){
      navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu');
      });
    }
    if (navClose){
      navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
      });
    }
    const navLink = document.querySelectorAll('.nav__link');

    function linkAction(){
      const navMenu = document.getElementById('nav-menu');
      // When we click on each nav__link, we remove the show-menu class
      navMenu.classList.remove('show-menu');
    }
    navLink.forEach(n => n.addEventListener('click', linkAction));

    /* ==================== CHANGE BACKGROUND HEADER ====================*/
    function scrollHeader(){
      const header = document.getElementById('header');
      // When the scroll is greater than 100 viewport height, add the scroll-header class to the header tag
      if (this.scrollY >= 100) {
        header.classList.add('scroll-header');
      } else {
        header.classList.remove('scroll-header');
      }
    }
    window.addEventListener('scroll', scrollHeader);
  },
};
</script>

<style scoped>

</style>

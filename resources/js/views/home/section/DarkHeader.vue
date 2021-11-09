<template>
  <header id="header" class="dark__header">
    <nav class="nav">
      <div class="header__wrapper">
        <a class="nav__logo" href="#">
          <img alt="" src="/images/logo-amdal-white.png">
        </a>
        |
        <h1>WEBGIS AMDALNET</h1>
      </div>

      <div id="nav-menu" class="nav__menu">
        <ul class="nav__list">
          <li class="nav__item">
            <router-link class="nav__link" to="/">Home</router-link>
          </li>
          <li class="nav__item">
            <router-link class="nav__link" to="dashboard">Dashboard</router-link>
          </li>
          <li class="nav__item">
            <button class="btn__link" @click="loginModalShow()">Masuk</button>
          </li>
        </ul>

        <i id="nav-close" class="ri-close-line nav__close" />
      </div>

      <div id="nav-toggle" class="nav__toggle">
        <i class="ri-function-line" />
      </div>
    </nav>
    <LoginModal
      :show="showIdDialog"
    />
  </header>
</template>

<script>
import LoginModal from '../../webgis/components/LoginModal';

export default {
  name: 'DarkHeaderHome',
  components: {
    LoginModal,
  },
  data() {
    return {
      loading: false,
      showIdDialog: false,
    };
  },
  created() {
    const navMenu = document.getElementById('nav-menu');
    const navToggle = document.getElementById('nav-toggle');
    const navClose = document.getElementById('nav-close');
    if (navToggle) {
      navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu');
      });
    }
    if (navClose) {
      navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
      });
    }
    const navLink = document.querySelectorAll('.nav__link');

    function linkAction() {
      const navMenu = document.getElementById('nav-menu');
      navMenu.classList.remove('show-menu');
    }

    navLink.forEach(n => n.addEventListener('click', linkAction));
  },
  methods: {
    loginModalShow() {
      this.showIdDialog = true;
    },
  },
};
</script>

<style scoped>
.header__wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header__wrapper h1 {
  color: white;
  font-weight: 600;
  margin-left: 30px;
}

.nav {
  height: 60px;
  padding: 10px;
}

@media screen and (max-width: 450px) {
  .header__wrapper h1 {
    display: none;
  }
}
</style>

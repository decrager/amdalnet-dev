<template>
  <header id="header" class="dark__header">
    <nav class="nav">
      <div class="header__wrapper">
        <a class="nav__logo" href="#">
          <img alt="" src="/images/logo-amdal-white.png">
        </a>
        |
        <h1>WEBGIS</h1>
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
            <router-link to="login" class="btn__link login_link">Masuk</router-link>
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
  name: 'DarkHeaderHome',
  data() {
    return {
      loading: false,
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
    toLogin() {
      this.loading = true;
      return this.$router.push('/login');
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

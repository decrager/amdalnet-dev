<template>
  <header id="header" class="header">
    <nav class="nav container">
      <a href="#" class="nav__logo" @click="handleSetMenu('LOGO')">
        <img src="/images/logo-amdal-white.png" alt="">
      </a>
      <div id="nav-menu" class="nav__menu">
        <ul class="nav__list">
          <li class="nav__item">
            <a href="#" class="nav__link" :class="{'active-link':(activeMenu === 'Materi')}" @click="handleSetMenu('MATERI')">Materi</a>
          </li>
          <li class="nav__item">
            <a href="#" class="nav__link" :class="{'active-link':(activeMenu === 'Formulir')}" @click="handleSetMenu('FORMULIR')">
              Formulir dan Standard
              <template v-if="isActiveFormulir">
                <i class="el-icon-arrow-up" />
              </template>
              <template v-else>
                <i class="el-icon-arrow-down" />
              </template>
            </a>
            <ul v-if="isActiveFormulir" class="left-4 top-1-8">
              <li>
                <a href="#" @click="handleSetMenu('TEMPLATE')">Template Persetujuan Perlingkupan</a>
              </li>
              <li>
                <a href="">Formulir KA Amdal Standar Spesifik</a>
              </li>
              <li>
                <a href="">UKL-UPL Standar Spesifik</a>
              </li>
              <li>
                <a href="">Lainnya...</a>
              </li>
            </ul>
          </li>
          <li class="nav__item" style="margin-top:2.2rem">
            <a href="#" class="nav__link" :class="{'active-link':(activeMenu === 'Daftar')}" @click="handleSetMenu('STATIC')">
              Data & Informasi<br>Persetujuan<br>Lingkungan
              <template v-if="isActiveDaftar">
                <i class="el-icon-arrow-up" />
              </template>
              <template v-else>
                <i class="el-icon-arrow-down" />
              </template>
            </a>
            <ul v-if="isActiveDaftar" class="left0 top4">
              <li>
                <a href="#" @click="handleSetMenu('IZIN')">Daftar Izin</a>
              </li>
              <li>
                <a href="">Startistik Izin</a>
              </li>
              <li>
                <a href="">Daftar LPJP</a>
              </li>
              <li>
                <a href="">Lainnya...</a>
              </li>
            </ul>
          </li>
          <li class="nav__item">
            <a href="#" class="nav__link" :class="{'active-link':(activeMenu === 'Kebijakan')}" @click="handleSetMenu('KEBIJAKAN')">Kebijakan</a>
          </li>
          <li class="nav__item">
            <a href="#about" class="nav__link" :class="{'active-link':(activeMenu === 'Tentang')}" @click="handleSetMenu('TENTANG')">Tentang Kami</a>
          </li>
          <li class="nav__item">
            <router-link to="login" class="btn__link">Masuk</router-link>
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
    return {
      loading: false,
      isActiveFormulir: false,
      isActiveDaftar: false,
      activeMenu: 'Home',
    };
  },
  mounted() {
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
      navMenu.classList.remove('show-menu');
    }
    navLink.forEach(n => n.addEventListener('click', linkAction));

    /* ==================== CHANGE BACKGROUND HEADER ====================*/
    function scrollHeader(){
      const header = document.getElementById('header');
      if (this.scrollY >= 100) {
        header.classList.add('scroll-header');
      } else {
        header.classList.remove('scroll-header');
      }
    }
    window.addEventListener('scroll', scrollHeader);
  },
  methods: {
    toLogin() {
      this.loading = true;
      return this.$router.push('/login');
    },
    handleSetMenu(e){
      if (e === 'LOGO'){
        this.activeMenu = 'Home';
      }
      if (e !== 'FORMULIR' && e !== 'DAFTAR'){
        this.$emit('handleSetMenu', e);
      }
      if (e === 'MATERI'){
        this.activeMenu = 'Materi';
      }
      if (e === 'FORMULIR'){
        this.isActiveFormulir = !this.isActiveFormulir;
        this.activeMenu = 'Formulir';
      }
      if (e === 'STATIC'){
        this.isActiveDaftar = !this.isActiveDaftar;
        this.activeMenu = 'Daftar';
      }
      if (e === 'KEBIJAKAN'){
        this.activeMenu = 'Kebijakan';
      }
      if (e === 'TENTANG'){
        this.activeMenu = 'Tentang';
      }
      if (e === 'IZIN' || e === 'TEMPLATE'){
        this.isActiveDaftar = false;
        this.isActive = false;
      }
    },
  },
};
</script>

<style scoped>
.nav__link {font-size: 0.9rem;}
  .nav__item{position: relative;}
  /*.nav__item ul{position: absolute;background: black;  padding: 1rem;border: 1px solid #847a7a;border-radius: 5px;width: 254px;font-size: 0.7rem;}*/
  .nav__item ul{position: absolute;top: 1.8rem;background: #637664;  padding: 1rem;width: 294px;font-size: 0.8rem;}
  .nav__item ul li{padding: 0.5rem;}
  .nav__item ul li a{color: #fff;}
  .left-4{left: -4rem;}
  .left0{left: 0;}
  .top-1-8{top: 1.8rem;}
  .top4{top: 4rem;}
</style>

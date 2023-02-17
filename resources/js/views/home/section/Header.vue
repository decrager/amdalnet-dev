<template>
  <header id="header" class="header">
    <nav class="nav container">
      <a href="#" class="nav__logo" @click="handleSetMenu('LOGO')">
        <el-row :gutter="5">
          <el-col :span="6" class="meaf-logo">
            <div><img src="/images/meaf_logo.png" alt=""></div>
          </el-col>
          <el-col :span="18">
            <img src="/images/logo-amdal-white.png" alt="">
          </el-col>
        </el-row>
      </a>
      <div id="nav-menu" class="nav__menu">
        <ul class="nav__list">
          <li class="nav__item">
            <a href="#" class="nav__link" :class="{'active-link':(activeMenu === 'Materis')}" @click="handleSetMenu('MATERIS')">Materi
              <template v-if="isActiveMateri">
                <i class="el-icon-arrow-up" />
              </template>
              <template v-else>
                <i class="el-icon-arrow-down" />
              </template>
            </a>
            <ul v-if="isActiveMateri" class="left-4 top-1-8">
              <li>
                <a href="#" @click="handleSetMenu('MATERI')">Materi</a>
              </li>
              <li>
                <a href="#" @click="handleSetMenu('VIDEOS')">Video Tutorial</a>
              </li>
              <li>
                <a href="#" @click="handleSetMenu('PANDUAN')">Panduan</a>
              </li>
            </ul>
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
                <a href="#" @click="handleSetMenu('TEMPLATE')">Template Persetujuan Lingkungan</a>
              </li>
              <li>
                <a href="">Formulir KA Andal Standar Spesifik</a>
              </li>
              <li>
                <a href="#">UKL-UPL Standar Spesifik</a>
                <i class="el-icon-arrow-down" />
                <ul>
                  <li><a href="#" @click="handleSetMenu('UKL_UPL_M')">Template UKL-UPL Menengah Rendah</a></li>
                  <li><a href="#" @click="handleSetMenu('UKL_UPL_S')">Template UKL-UPL Standar Spesifik</a></li>
                </ul>
              </li>
              <li>
                <a href="#" @click="handleSetMenu('SOP')">SOP Pengelolaan dan Pemantauan Lingkungan</a>
              </li>
              <li>
                <a href="#" @click="handleSetMenu('CLUSTER')">Daftar Cluster KBLI UKL UPL Menengah Rendah</a>
              </li>
            </ul>
          </li>
          <li class="nav__item" style="margin-top:1.2rem">
            <a href="#" class="nav__link" :class="{'active-link':(activeMenu === 'Daftar')}" @click="handleSetMenu('STATIC')">
              Data &amp; Informasi
              <template v-if="isActiveDaftar">
                <i class="el-icon-arrow-up" />
              </template>
              <template v-else>
                <i class="el-icon-arrow-down" />
              </template>
              <br>Persetujuan Lingkungan
            </a>
            <ul v-if="isActiveDaftar" class="left0 top4">
              <li>
                <a href="#" @click="handleSetMenu('IZIN')">Daftar Persetujuan Lingkungan</a>
              </li>
              <!-- <li>
                <a href="">Statistik Izin</a>
              </li> -->
              <li>
                <a href="#" @click="handleSetMenu('LPJP')">Daftar LPJP</a>
              </li>
              <li>
                <a href="#" @click="handleSetMenu('Pemerintah')">Daftar Instansi Pemerintah</a>
              </li>
              <li>
                <a href="#" @click="handleSetMenu('Penyusun')">Daftar Penyusun</a>
              </li>
              <li>
                <a href="#" @click="handleSetMenu('TUK')">Daftar TUK</a>
              </li>
              <li>
                <a href="https://amdal.menlhk.go.id/info_persuratan" target="_blank">Informasi Persuratan</a>
              </li>
              <li>
                <a href="https://amdal.menlhk.go.id/ukl_upl_mr" target="_blank">Data SPPL dan UKL-UPL Menengah Rendah</a>
              </li>
            </ul>
          </li>
          <li class="nav__item">
            <a href="#" class="nav__link" :class="{'active-link':(activeMenu === 'Kebijakan')}" @click="handleSetMenu('KEBIJAKAN')">Kebijakan</a>
          </li>
          <!-- <li class="nav__item">
            <a href="#about" class="nav__link" :class="{'active-link':(activeMenu === 'Tentang')}" @click="handleSetMenu('TENTANG')">Tentang Kami</a>
            <a href="" class="nav__link" :class="{'active-link':(activeMenu === '')}">Tentang Kami</a>
          </li> -->
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
  name: 'HeaderHome',
  data() {
    return {
      loading: false,
      isActiveFormulir: false,
      isActiveDaftar: false,
      isActiveMateri: false,
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
      if (e !== 'FORMULIR' && e !== 'DAFTAR' && e !== 'STATIC' && e !== 'MATERIS'){
        this.$emit('handleSetMenu', e);
      }
      if (e === 'MATERIS' || e === 'MATERI' || e === 'PANDUAN' || e === 'VIDEOS'){
        this.isActiveMateri = !this.isActiveMateri;
        this.activeMenu = 'Materis';
      }
      if (e === 'FORMULIR' || e === 'UKL_UPL_M' || e === 'UKL_UPL_S' || e === 'SOP' || e === 'CLUSTER'){
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
        alert('Sedang dalam pengembangan');
        // this.activeMenu = 'Tentang';
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
.nav__link,.login_link {font-size: 0.8rem;}
  .nav__item{position: relative;}
  /*.nav__item ul{position: absolute;background: black;  padding: 1rem;border: 1px solid #847a7a;border-radius: 5px;width: 254px;font-size: 0.7rem;}*/
  .nav__item > ul{position: absolute;top: 1.8rem;background: #637664;  width: 294px;font-size: 0.8rem;z-index: 999;}
  .nav__item > ul li{padding: 0.5rem; position: relative;}
  /* .nav__item > ul > li:hover a{color: #143b17;} */
  .nav__item > ul li a{color: #fff;}
  .left-4{left: -4rem;}
  .left0{left: 0;}
  .top-1-8{top: 1.8rem;}
  .top4{top: 4rem;}
  .nav__item > ul li ul{position: absolute; left: 100%;background: #637664;  width: 294px;font-size: 0.8rem; top: 0;display: none;}
  .nav__item > ul li:hover ul{display: block;}
  .nav__item > ul li:hover i{transform: rotate(-90deg);}
  .nav__item > ul li i {color: #133815;font-weight: bold;display: inline-block;float: right;}

@media screen and (min-width: 451px) {
  .nav__logo .meaf-logo div{
    text-align: left !important;
    padding-right: 1.5em;
    margin: 1.1em  auto 0.8em;
    border-right: 3px solid #092e0b;
  }
}
</style>

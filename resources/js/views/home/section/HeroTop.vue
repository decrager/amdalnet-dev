<template>
  <section id="home" class="home">
    <img src="/images/cover.png" alt="" class="home__img">

    <div class="home__container container grid">
      <div class="home__data">
        <span class="home__data-title">AMDALNET</span>
        <h1 class="home__data-subtitle">Sistem Informasi Dokumen<br> Lingkungan Hidup</h1>
        <router-link to="login" class="button">Ajukan Persetujuan Lingkungan</router-link>
      </div>

    </div>
  </section>
</template>

<script>
export default {
  name: 'HeroHome',
};
</script>

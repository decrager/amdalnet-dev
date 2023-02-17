<template>
  <div class="prelogin">
    <div class="main">
      <img src="images/email.png">
      <h1>
        Periksa Inbox Email Anda.
      </h1>
      <p>
        Kami telah mengirimkan email konfirmasi ke email anda. Tekan tombok aktivasi pada email tersebut untuk mengaktivasi akun anda.
      </p>
      <p>
        Anda tidak akan bisa login ke Akun Amdalnet anda sebelum melakukan aktivasi akun.
      </p>
      <router-link to="login" class="button bottom">Kembali ke Login</router-link>
    </div>
  </div>
</template>

<script>
export default {
};

</script>

<style rel="stylesheet/scss" lang="scss" scoped>

.prelogin {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background-image: url('../../assets/login/cover.png');
  transition: background-color .3s ease-in-out;
  overflow: auto;
  background-size: cover;
  background-position: center;
}
.main {
  padding: 20px;
  border-radius: 20px;
  height: 400px;
  width: 900px;
  display: block;
  text-align: center;
  align-items: center;
  justify-content: center;
  background-color: #ffffff;
  position: relative;
}
.bottom {
  padding: 30px;
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
}

</style>

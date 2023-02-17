<template>
  <div class="prelogin">
    <div class="main">
      <img src="images/email.png">
      <h1>
        Selamat Akun Anda telah Berhasil Didaftarkan
        <br>Silahkan Periksa Inbox Email Anda Untuk Aktivasi Akun
      </h1>
      <p>
        Kami telah mengirimkan email konfirmasi ke alamat email anda. Tekan tombol aktivasi pada email tersebut untuk mengaktivasi akun anda.
      </p>
      <p>
        <br>
        Anda tidak akan bisa login ke Akun Amdalnet anda sebelum melakukan aktivasi akun.
      </p>
      <div class="bottom">
        <router-link to="login" class="warning-button">Kembali ke Login</router-link>
      </div>
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
  height: fit-content;
  min-height: 400px;
  width: 900px;
  display: block;
  text-align: center;
  align-items: center;
  justify-content: center;
  background-color: #ffffff;
  // position: relative;
}
.bottom {
  padding: 20px;
  // position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  align-items: flex-end;
}
.warning-button {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 5px;
  border-radius: 5px;
  height: 50px;
  width: fit-content;
  font-weight: bold;
  color: #ffffff;
  text-align: center;
  background-color: #FFA84C;
}

</style>

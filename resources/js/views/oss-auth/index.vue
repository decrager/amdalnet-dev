<template>
  <div v-loading.fullscreen.lock="fullscreenLoading" />
</template>

<script>
import axios from 'axios';
// import Resource from '@/api/resource';
import { isLogged, setLogged } from '@/utils/auth';

export default {
  name: 'OssAuth',
  data() {
    return {
      fullscreenLoading: false,
      to: this.$route.query.to,
      email: this.$route.query.email,
      username: this.$route.query.username,
      token: this.$route.query.token,
      userInfo: {},
      isTokenValid: false,
      isUserExists: false,
    };
  },
  mounted() {
    this.fullscreenLoading = true;
    this.isEmailRegistered()
      .then(response => {
        this.validateToken()
          .then(response => {
            // console.log('this.isUserExists = ' + this.isUserExists);
            // console.log('this.isTokenValid = ' + this.isTokenValid);
            this.redirect()
              .then(response => {
                this.fullscreenLoading = false;
              });
          });
      });
  },
  methods: {
    async redirect() {
      if (this.isUserExists && this.isTokenValid) {
        // const isL = isLogged();
        // console.log('isL = ' + isL);
        if (isLogged()) {
          // logout
          this.$store.dispatch('user/logout')
            .then(response => {
              this.loginOss();
            });
        } else {
          this.loginOss();
        }
      } else if (this.isUserExists && !this.isTokenValid) {
        this.$message({
          message: 'Token telah kedaluarsa. Silakan login kembali di website OSS',
          type: 'error',
          duration: 5 * 1000,
        });
        this.$router.push({ path: '/' });
      } else if (!this.isUserExists) {
        this.getUserInfo()
          .then(response => {
            // Register
            this.$router.push({
              name: 'OssAuthRegister',
              params: {
                userInfo: {
                  email: this.email,
                  username: this.username,
                  name: this.userInfo.nama, // TODO: get detail NIB from OSShub
                  pic: this.userInfo.nama,
                  phone: this.userInfo.telp,
                  address: this.userInfo.alamat,
                  nib: this.userInfo.data_nib[0],
                },
              },
            });
          });
      }
    },
    loginOss() {
      // login with OSS
      axios.post('api/auth/login-oss', {
        email: this.email,
        token: this.token,
      })
        .then(response => {
          if (response.status === 200) {
            setLogged('1');
            this.$message({
              message: 'Anda berhasil login sebagai ' + this.email,
              type: 'success',
              duration: 5 * 1000,
            });
            this.$router.push({ path: this.to });
          } else {
            console.log(response.data.message);
          }
        })
        .catch(err => {
          console.log(err);
          this.$message({
            message: 'Login gagal. Pastikan anda telah login di web OSS.',
            type: 'error',
            duration: 5 * 1000,
          });
        });
    },
    async isEmailRegistered() {
      this.isUserExists = false;
      await axios.get('api/auth/is-email-registered?email=' + this.email)
        .then(response => {
          if (response.status === 200) {
            this.isUserExists = true;
          }
        });
    },
    async validateToken() {
      this.isTokenValid = false;
      await axios.post('api/auth/validate-token', {
        token: this.token,
      })
        .then(response => {
          if (response.status === 200) {
            this.isTokenValid = true;
          }
        });
    },
    async getUserInfo() {
      await axios.get('api/auth/userinfo-oss?token=' + this.token)
        .then(response => {
          if (response.status === 200) {
            this.userInfo = response.data.data;
          }
        });
    },
  },
};
</script>

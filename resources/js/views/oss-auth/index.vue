<template>
  <div v-loading.fullscreen.lock="fullscreenLoading" />
</template>

<script>
import axios from 'axios';
import { isLogged, setLogged } from '@/utils/auth';

export default {
  name: 'OssAuth',
  data() {
    return {
      fullscreenLoading: false,
      to: this.$route.query.to,
      token: this.$route.query['access-token'],
      refresh_token: this.$route.query['refresh_token'],
      id_izin: this.$route.query['id_izin'],
      kd_izin: this.$route.query['kd_izin'],
      userInfo: {},
      isTokenValid: false,
      isUserExists: false,
      receiveTokenSuccess: false,
    };
  },
  mounted() {
    this.fullscreenLoading = true;
    console.log('this.token = ' + this.$route.query['access-token']);
    if (this.token === undefined || this.refresh_token === undefined ||
      this.id_izin === undefined || this.kd_izin === undefined) {
      this.fullscreenLoading = false;
      this.$message({
        message: 'Link tidak valid. Silakan coba lagi.',
        type: 'error',
        duration: 5 * 1000,
      });
      this.$router.push({ path: '/' });
    }
    if (this.$route.query.to === '' || this.$route.query.to === undefined) {
      this.to = '/dashboard';
    }
    this.main();
  },
  methods: {
    async main() {
      this.validateToken()
        .then(response => {
          console.log('isTokenValid = ' + this.isTokenValid);
          if (this.isTokenValid) {
            this.getUserInfo()
              .then(response => {
                console.log('this.userInfo = ' + JSON.stringify(this.userInfo));
                if (this.userInfo.email === undefined) {
                  this.$message({
                    message: 'User tidak valid',
                    type: 'error',
                    duration: 5 * 1000,
                  });
                  this.$router.push({ path: '/' });
                } else {
                  this.isEmailRegistered()
                    .then(response => {
                      this.redirect()
                        .then(response => {
                          this.fullscreenLoading = false;
                        });
                    });
                }
              });
          } else {
            console.log('invalid token. recalling main()');
            setTimeout(() => {
              console.log('updating token...');
              this.updateToken()
                .then(response => {
                  this.main();
                  console.log('called main() function');
                });
            }, 1000);
          }
        });
    },
    async redirect() {
      console.log('this.isUserExists = ' + this.isUserExists);
      console.log('this.isTokenValid = ' + this.isTokenValid);
      if (this.isUserExists && this.isTokenValid) {
        if (isLogged()) {
          // logout
          this.$store.dispatch('user/logout')
            .then(response => {
              this.loginOss();
            });
        } else {
          this.loginOss();
        }
      } else if (!this.isUserExists) {
        // Register
        this.registerOss()
          .then(response => {
            console.log('registerOss = ' + JSON.stringify(response));
            if (this.isUserExists) {
              this.loginOss();
            }
          });
      }
    },
    async registerOss() {
      const regForm = {
        email: this.userInfo.email,
        name: this.userInfo.nama,
        pic: this.userInfo.nama,
        username: this.userInfo.username,
        password: '',
        user_type: 'Pemrakarsa',
        phone: this.userInfo.telp,
        address: this.userInfo.alamat,
        nib: this.userInfo.data_nib[0],
      };
      axios.post('api/auth/register-oss', regForm)
        .then(response => {
          if (parseInt(response.data.status) === 200) {
            this.isUserExists = true;
          }
        });
    },
    loginOss() {
      // login with OSS
      axios.post('api/auth/login-oss', {
        email: this.userInfo.email,
        token: this.token,
      })
        .then(response => {
          if (response.status === 200) {
            setLogged('1');
            this.$message({
              message: 'Anda berhasil login sebagai ' + this.userInfo.email,
              type: 'success',
              duration: 5 * 1000,
            });
            this.receiveToken()
              .then(response => {
                console.log('this.receiveTokenSuccess = ' + this.receiveTokenSuccess);
                if (this.receiveTokenSuccess) {
                  this.$router.push({ path: this.to });
                }
              });
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
      await axios.get('api/auth/is-email-registered?email=' + this.userInfo.email)
        .then(response => {
          if (parseInt(response.data.status) === 200) {
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
          if (parseInt(response.data.status) === 200) {
            this.isTokenValid = true;
          }
        });
    },
    async updateToken() {
      this.isTokenValid = false;
      await axios.post('api/auth/update-token', {
        refresh_token: this.refresh_token,
      })
        .then(response => {
          console.log('updateToken response.data = ' + JSON.stringify(response.data));
          if (parseInt(response.data.code) === 200) {
            this.isTokenValid = true;
            this.token = response.data.data.access_token;
            console.log('updated new token = ' + this.token);
          }
        });
    },
    async receiveToken() {
      this.isTokenValid = false;
      await axios.get('api/auth/receive-token', {
        params: {
          'access-token': this.token,
          refresh_token: this.refresh_token,
          kd_izin: this.kd_izin,
          id_izin: this.id_izin,
        },
      })
        .then(response => {
          console.log('receiveToken = ' + JSON.stringify(response));
          if (parseInt(response.data.status) === 200) {
            this.receiveTokenSuccess = true;
          }
        });
    },
    async getUserInfo() {
      await axios.get('api/auth/userinfo-oss?token=' + this.token)
        .then(response => {
          if (parseInt(response.data.status) === 200) {
            this.userInfo = response.data.data;
          }
        });
    },
  },
};
</script>

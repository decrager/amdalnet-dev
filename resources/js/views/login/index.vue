<template>
  <div class="login">
    <div v-if="form === 'login'" class="login-container">
      <div class="login-content">
        <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="on" label-position="left">
          <div class="title-wrap">
            <img
              class="logo"
              alt="Amdalnet"
              :src="logo"
            >
            <!-- h3 class="title">
              {{ $t('login.title') }}
              <lang-select class="set-language" />
            </h3 -->
          </div>
          <el-form-item prop="email">
            <span class="svg-container">
              <svg-icon icon-class="user" />
            </span>
            <el-input v-model="loginForm.email" name="email" type="text" auto-complete="on" :placeholder="$t('login.email')" />
          </el-form-item>
          <el-form-item prop="password">
            <span class="svg-container">
              <svg-icon icon-class="password" />
            </span>
            <el-input
              v-model="loginForm.password"
              name="password"
              auto-complete="on"
              placeholder="password"
              :type="pwdType"
              @keyup.enter.native="handleLogin"
            />
            <span class="show-pwd" @click="showPwd">
              <svg-icon icon-class="eye" />
            </span>
          </el-form-item>
          <el-form-item>
            <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleLogin">
              {{ $t('login.logIn') }}
            </el-button>
          </el-form-item>
          <el-row type="flex" class="row-bg" justify="space-between">
            <el-button type="text" style="background-color: transparent; color: blue;">Lupa Kata Sandi?</el-button>
            <el-button type="text" style="background-color: transparent; color: blue;" @click="handleOpenRegister">Tidak Memiliki Akun? <span style="color: red">Buat Akun Baru</span> </el-button>
          </el-row>
          <div class="tips">
            <span style="margin-right:20px;">Email: admin@laravue.dev</span>
            <span>Password: laravue</span>
          </div>
        </el-form>
      </div>
    </div>
    <div v-else class="registration-container">
      <el-form ref="registrationForm" :model="registrationForm" :rules="registrationRules" class="registration-form" auto-complete="on" label-position="top">
        <h2>{{ $t('login.registrationForm') }}</h2>
        <el-form-item prop="user_type" :label="$t('login.userType')">
          <el-select
            v-model="registrationForm.user_type"
            name="user_type"
            :placeholder="$t('login.userType')"
            style="width: 100%"
          >
            <el-option
              v-for="item in userTypeoptions"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item prop="name" :label="$t('login.name')">
          <el-input v-model="registrationForm.name" name="name" type="text" auto-complete="on" :placeholder="$t('login.name')" suffix-icon="el-icon-user" />
        </el-form-item>
        <el-form-item prop="pic" :label="$t('login.pic')">
          <el-input v-model="registrationForm.pic" name="pic" type="text" auto-complete="on" :placeholder="$t('login.pic')" suffix-icon="el-icon-user" />
        </el-form-item>
        <el-form-item prop="address" :label="$t('login.address')">
          <el-input v-model="registrationForm.address" name="address" type="textarea" auto-complete="on" :placeholder="$t('login.address')" />
        </el-form-item>
        <el-form-item prop="email" :label="$t('login.email')">
          <el-input v-model="registrationForm.email" name="email" type="text" auto-complete="on" :placeholder="$t('login.email')" suffix-icon="el-icon-message" />
        </el-form-item>
        <el-form-item prop="phone" :label="$t('login.phone')">
          <el-input v-model="registrationForm.phone" name="phone" type="text" auto-complete="on" :placeholder="$t('login.phone')" suffix-icon="el-icon-phone" />
        </el-form-item>
        <el-row type="flex" class="row-bg" justify="space-between">
          <el-button type="text" style="background-color: transparent; color: blue;" @click="handleCancelReg">Sudah Memiliki Akun?</el-button>
          <el-button type="warning" :loading="loading" size="mini" @click="handleReg">Buat</el-button>
        </el-row>
      </el-form>
    </div>
  </div>
</template>

<script>
// import LangSelect from '@/components/LangSelect';
import { validEmail } from '@/utils/validate';
import { csrf } from '@/api/auth';
import Resource from '@/api/resource';
const initiatorResource = new Resource('initiators');

const logo = require('@/assets/login/logo-amdal.png').default;

export default {
  name: 'Login',
  // components: { LangSelect },
  data() {
    const validateEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error('Please enter the correct email'));
      } else {
        callback();
      }
    };
    const validatePass = (rule, value, callback) => {
      if (value.length < 4) {
        callback(new Error('Password cannot be less than 4 digits'));
      } else {
        callback();
      }
    };
    return {
      loginForm: {
        email: 'admin@laravue.dev',
        password: 'laravue',
      },
      loginRules: {
        email: [{ required: true, trigger: 'blur', validator: validateEmail }],
        password: [{ required: true, trigger: 'blur', validator: validatePass }],
      },
      registrationRules: {
        user_type: [{ required: true, trigger: 'change', message: 'Pilih Jenis User' }],
        name: [{ required: true, trigger: 'blur' }],
        pic: [{ required: true, trigger: 'blur' }],
        address: [{ required: true, trigger: 'blur' }],
        email: [{ required: true, trigger: 'blur' }],
        phone: [{ required: true, trigger: 'blur' }],
      },
      loading: false,
      pwdType: 'password',
      redirect: undefined,
      otherQuery: {},
      logo: logo,
      registrationForm: {},
      userTypeoptions: [
        {
          value: 'Pemrakarsa',
          label: 'Pemrakarsa',
        },
        {
          value: 'Pemerintah',
          label: 'Pemerintah',
        },
      ],
      form: 'login',
    };
  },
  watch: {
    $route: {
      handler: function(route) {
        const query = route.query;
        if (query) {
          this.redirect = query.redirect;
          this.otherQuery = this.getOtherQuery(query);
        }
      },
      immediate: true,
    },
  },
  methods: {
    showPwd() {
      if (this.pwdType === 'password') {
        this.pwdType = '';
      } else {
        this.pwdType = 'password';
      }
    },
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true;
          csrf().then(() => {
            this.$store.dispatch('user/login', this.loginForm)
              .then(() => {
                this.$router.push({ path: this.redirect || '/dashboard', query: this.otherQuery }, onAbort => {});
                this.loading = false;
                window.location.reload();
              })
              .catch(() => {
                this.loading = false;
              });
          });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== 'redirect') {
          acc[cur] = query[cur];
        }
        return acc;
      }, {});
    },
    handleCancelReg(){
      this.form = 'login';
    },
    handleOpenRegister(){
      this.form = 'register';
    },
    handleReg(){
      this.$refs.registrationForm.validate(valid => {
        if (valid) {
          this.loading = true;
          csrf().then(() => {
            initiatorResource
              .store(this.registrationForm)
              .then((response) => {
                this.$message({
                  message:
                'User Dengan Email ' +
                this.registrationForm.email +
                ' Berhasil Dibuat',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.loading = false;
                this.form = 'login';
                this.registrationForm = {};
              })
              .catch((error) => {
                console.log(error);
              });
          });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss">
$bg:#2d3a4b;
$light_gray:#5F6368;

/* reset element-ui css */
.login-container {
  .el-input {
    display: inline-block;
    height: 47px;
    width: 85%;
    input {
      background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 47px;
      &:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
                box-shadow: 0 0 0px 1000px $bg inset !important;
        -webkit-text-colorfill-color: rgb(8, 7, 7) !important;
      }
    }
  }
  .el-form-item {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    color: #454545;
  }
}
</style>

<style rel="stylesheet/scss" lang="scss" scoped>

$bg:#2d3a4b;
$dark_gray:#889aa4;
$light_gray:rgb(7, 6, 6);
$bgColor: #143A16;
$brown: #B27C66;
$textColor:#eee;

.login {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background-color: $bgColor;
  background-image: url('../../assets/login/cover.png');
  transition: background-color .3s ease-in-out;
  overflow: auto;
  background-size: cover;
  background-position: center;

  .login-container {
    // background: $bg;
    // width: 1120px;
    // min-height: 590px;
    // display: grid;
    text-align: center;
    margin: auto;
    grid-template-columns: auto 480px;
    transition: all .3s ease-in-out;
    transform: scale(1);

    .logo {
      display: inline-block;
      margin-bottom: 20px;
      width: 250px;
    }

    // .login-image {
    //   display: flex;
    //   flex-direction: row;
    //   justify-content: flex-end;
    //   overflow: hidden;
    //   background-color: #303c4b;
    //   background-image: url('../../assets/login/cover.png');
    //   background-position: 50%;
    //   background-size: cover;
    //   opacity: 1;
    //   transition: opacity .3s ease-in-out,padding .2s ease-in-out;

    //   .photo-credit {
    //     justify-content: flex-end;
    //     align-self: flex-end;
    //     background-color: rgba(255,255,255,0.8);
    //     margin: 10px;
    //     padding: 5px 8px;

    //     h4, span { margin: 0; }
    //   }
    // }
    .el-button{
      background-color: #FFA84C;
    }
    .login-form {
      width: 50vw;
      min-width: 360px;
      max-width: 560px;
      margin: auto;
      background: #fff;
      padding: 60px 60px;
      position: relative;
      opacity: 1;
      transition: opacity .3s ease-in-out,padding .2s ease-in-out;
    }
    .tips {
      font-size: 14px;
      color: #fff;
      margin-bottom: 10px;
      span {
        &:first-of-type {
          margin-right: 16px;
        }
      }
    }
    .svg-container {
      padding: 6px 5px 6px 15px;
      color: $dark_gray;
      vertical-align: middle;
      width: 30px;
      display: inline-block;
    }
    .title-wrap {
      display: block;
      margin-bottom: 15px;

      .title {
        font-size: 24px;
        color: $textColor;
        margin: 0px auto 10px auto;
        text-align: left;
        font-weight: bold;
      }
      .sub-heading {
        font-size: 14px;
        color: $textColor;
        padding-bottom: 15px;
      }
    }

    .show-pwd {
      position: absolute;
      right: 10px;
      top: 7px;
      font-size: 16px;
      color: $dark_gray;
      cursor: pointer;
      user-select: none;
    }
    .set-language {
      color: $textColor;
      position: absolute;
      top: 40px;
      right: 35px;
    }
  }
  .registration-container{
    margin: auto;
    grid-template-columns: auto 480px;
    transition: all .3s ease-in-out;
    transform: scale(1);
    .registration-form{
      width: 50vw;
      min-width: 360px;
      max-width: 560px;
      margin: auto;
      background: #fff;
      padding: 50px 60px;
      position: relative;
      opacity: 1;
      transition: opacity .3s ease-in-out,padding .2s ease-in-out;
      border-radius: 20px;
    }
  }
}
</style>

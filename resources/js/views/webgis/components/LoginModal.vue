<template>
  <div class="modal__container">
    <el-dialog :title="'Login Amdalnet'" :visible.sync="show" :close-on-click-modal="false" :show-close="false" width="35%" :modal-append-to-body="false">
      <div class="login-container">
        <div class="login-content">
          <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="on" label-position="left">
            <div class="title-wrap">
              <img
                class="logo"
                alt="Amdalnet"
                :src="logo"
              >
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
              <el-button :loading="loading" class="button-login" type="primary" style="width:100%;" @click.native.prevent="handleLogin">
                {{ $t('login.logIn') }}
              </el-button>
            </el-form-item>
            <el-form-item>
              <el-button type="danger" class="button-cancel" style="width:100%;" @click="closeDialog()">
                Tutup
              </el-button>
            </el-form-item>
            <div class="tips">
              <span style="margin-right:20px;">Email: admin@laravue.dev</span>
              <span>Password: laravue</span>
            </div>
          </el-form>
        </div>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { validEmail } from '@/utils/validate';
import { csrf } from '@/api/auth';
const logo = require('@/assets/login/logo-amdal.png').default;

export default {
  name: 'LoginModal',
  props: {
    feedback: {
      type: Object,
      default: () => {},
    },
    show: Boolean,
  },
  data(){
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
      data: {},
      errorMessage: null,
      loginForm: {
        email: 'admin@amdalnet.dev',
        password: 'amdalnet',
      },
      loginRules: {
        email: [{ required: true, trigger: 'blur', validator: validateEmail }],
        password: [{ required: true, trigger: 'blur', validator: validatePass }],
      },
      loading: false,
      pwdType: 'password',
      redirect: undefined,
      otherQuery: {},
      logo: logo,
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
    closeDialog() {
      this.show = false;
    },
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
                this.$router.push({ path: '/dashboard', query: this.otherQuery }, onAbort => {});
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
  },
};
</script>

<style scoped>
.input__wrapper {
  display: grid;
  grid-template-columns: 2fr 2fr;
  column-gap: 2rem;
}

.label__peran {
  font-weight: 700;
  line-height: 36px;
}

.select__peran {
  width: 100%;
}
</style>

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
      // background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 47px;
      &:-webkit-autofill {
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

  .login-container {
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
    .el-button.button-login{
      background-color: #FFA84C;
    }
    .el-button.button-cancel{
      color: white;
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
</style>

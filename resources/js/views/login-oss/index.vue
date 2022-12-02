<template>
  <div class="login">
    <div class="login-container">
      <div class="login-content">
        <el-form
          ref="loginForm"
          :model="loginForm"
          :rules="loginRules"
          class="login-form"
          auto-complete="on"
          label-position="left"
        >
          <div class="title-wrap">
            <h3 class="title">Login OSS RBA</h3>
          </div>
          <el-form-item prop="username" class="my-2">
            <span class="svg-container">
              <svg-icon icon-class="user" />
            </span>
            <el-input
              v-model="loginForm.username"
              name="username"
              type="text"
              auto-complete="on"
              :placeholder="$t('login.usernameOSS')"
            />
          </el-form-item>
          <el-form-item prop="password" class="my-2">
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
            <!-- <el-button type="text" style="background-color: transparent; color: blue;" @click="handleOpenResetPassword">Lupa Kata Sandi?</el-button> -->
            <p style="background-color: transparent; color: blue;">
              <router-link to="/login">Login Amdalnet</router-link>
            </p>
          </el-row>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import { csrf } from '@/api/auth';

export default {
  name: 'Login',
  data() {
    return {
      loginForm: {},
      loading: false,
      pwdType: 'password',
      loginRules: {
        username: [{ required: true, trigger: 'blur' }],
        password: [{ required: true, trigger: 'blur' }],
      },
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
            this.$store.dispatch('user/loginOss', this.loginForm)
              .then((response) => {
                this.$router.push({ path: '/oss/receive-token', query: response.data }, onAbort => {});
                this.loading = false;
                // window.location.reload();
              })
              .catch((error) => {
                console.log(error);
                this.$message({
                  message: 'Maaf Email atau Password yang anda masukkan kurang tepat',
                  type: 'error',
                  duration: 5 * 1000,
                });
                this.loading = false;
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
$bg: #2d3a4b;
$light_gray: #5F6368;

/* reset element-ui css */
.login-container {
  .el-input {
    display: inline-block;
    height: 47px;
    width: 85%;

    input {
      background: transparent;
      border: 0;
      -webkit-appearance: none;
      border-radius: 0;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 47px;

      &:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px $bg inset !important;
        box-shadow: 0 0 0 1000px $bg inset !important;
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

.el-upload-dragger {
  width: auto;
}

.form-btn-cert {
  display: inline-block;
}

.alert-certificate {
  width: 37%;
}

.alert-certificate button.el-button--primary {
  background-color: #ff4949;
  border-color: #ff4949;
}
</style>

<style rel="stylesheet/scss" lang="scss" scoped>

$bg: #2d3a4b;
$dark_gray: #889aa4;
$light_gray: rgb(7, 6, 6);
$bgColor: #143A16;
$brown: #B27C66;
$textColor: #eee;

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
    .el-button {
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
      transition: opacity .3s ease-in-out, padding .2s ease-in-out;
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
        color: black;
        margin: 0 auto 10px auto;
        text-align: center;
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

  .registration-container {
    margin: auto;
    grid-template-columns: auto 480px;
    transition: all .3s ease-in-out;
    transform: scale(1);
    max-width: 882px;

    .registration-form {
      width: 50vw;
      min-width: 360px;
      max-width: 560px;
      margin: auto;
      background: #fff;
      padding: 50px 60px;
      position: relative;
      opacity: 1;
      transition: opacity .3s ease-in-out, padding .2s ease-in-out;
      border-radius: 20px;
    }
  }
}

.el-row {
  margin-bottom: 5px;

  &:last-child {
    margin-bottom: 0;
  }
}

.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.avatar-uploader .el-upload:hover {
  border-color: #409EFF;
}

.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}

.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>

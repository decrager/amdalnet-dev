<template>
  <div class="login">
    <div class="login-container">
      <div class="login-content">
        <el-form ref="regForm" :model="regForm" :rules="regRules" class="login-form" auto-complete="on" label-position="left">
          <div class="title-wrap">
            <img
              class="logo"
              alt="Amdalnet"
              :src="logo"
            >
          </div>
          <div align="center">
            <h3>{{ $t('login.registrationForm') }}</h3>
          </div>
          <el-form-item prop="email">
            <span class="svg-container">
              <svg-icon icon-class="user" />
            </span>
            <el-input v-model="regForm.email" name="email" type="text" auto-complete="on" :placeholder="$t('login.email')" disabled="true" />
          </el-form-item>
          <el-form-item prop="username">
            <span class="svg-container">
              <svg-icon icon-class="user" />
            </span>
            <el-input v-model="regForm.username" name="username" type="text" auto-complete="on" :placeholder="$t('login.username')" disabled="true" />
          </el-form-item>
          <el-form-item prop="name">
            <span class="svg-container">
              <svg-icon icon-class="user" />
            </span>
            <el-input v-model="regForm.name" name="name" type="text" auto-complete="on" :placeholder="$t('login.name')" disabled="true" />
          </el-form-item>
          <el-form-item prop="password">
            <span class="svg-container">
              <svg-icon icon-class="password" />
            </span>
            <el-input
              v-model="regForm.password"
              name="password"
              auto-complete="on"
              placeholder="password"
              :type="pwdType"
              @keyup.enter.native="handleRegister"
            />
            <span class="show-pwd" @click="showPwd">
              <svg-icon icon-class="eye" />
            </span>
          </el-form-item>
          <el-form-item>
            <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleRegister">
              {{ $t('login.register') }}
            </el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import { csrf } from '@/api/auth';
// import Resource from '@/api/resource';
import axios from 'axios';
// const initiatorResource = new Resource('initiators');
const logo = require('@/assets/login/logo-amdal.png').default;

export default {
  name: 'OssAuthRegister',
  props: {
    userInfo: {
      type: Object,
      default: () => {},
    },
    to: {
      type: String,
      default: () => '/',
    },
    token: {
      type: String,
      default: () => '',
    },
  },
  data() {
    const validatePass = (rule, value, callback) => {
      if (value.length < 4) {
        callback(new Error('Password cannot be less than 4 digits'));
      } else {
        callback();
      }
    };
    return {
      regForm: {
        email: this.userInfo.email,
        name: this.userInfo.name, // company name
        pic: this.userInfo.pic, // person name
        username: this.userInfo.username,
        password: '',
        user_type: 'Pemrakarsa',
        phone: this.userInfo.phone,
        address: this.userInfo.address,
        nib: this.userInfo.nib,
      },
      regRules: {
        password: [{ required: true, trigger: 'blur', validator: validatePass }],
      },
      pwdType: 'password',
      loading: false,
      logo: logo,
    };
  },
  methods: {
    async handleRegister() {
      // console.log(this.regForm);
      this.$refs.regForm.validate(valid => {
        if (valid) {
          this.regForm.user_type = 'Pemrakarsa';
          this.loading = true;
          csrf().then(() => {
            axios.post('api/auth/register-oss', this.regForm)
              .then(response => {
                if (response.status === 200) {
                  this.$message({
                    message: 'User Dengan Email ' + this.regForm.email + ' Berhasil Dibuat',
                    type: 'success',
                    duration: 5 * 1000,
                  });
                  // login & go to user profile
                  // this.$store.dispatch('user/login', {
                  //   email: this.regForm.email,
                  //   password: this.regForm.password,
                  // })
                  //   .then(() => {
                  //     this.loading = false;
                  //     this.$router.push({ path: '/profile/edit' }, onAbort => {});
                  //   })
                  //   .catch(() => {
                  //     this.loading = false;
                  //     const toPath = '/oss-auth?to=' + this.to + '&token=' + this.token;
                  //     this.$router.push({ path: toPath });
                  //   });
                  this.loading = false;
                  const toPath = '/oss-auth?to=' + this.to + '&token=' + this.token;
                  // const toPath = '/oss-auth?to=/profile/edit&token=' + this.token;
                  this.$router.push({ path: toPath });
                }
              });
          });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    showPwd() {
      if (this.pwdType === 'password') {
        this.pwdType = '';
      } else {
        this.pwdType = 'password';
      }
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
.el-upload-dragger{
   width: auto;
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

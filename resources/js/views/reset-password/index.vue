<template>
  <div class="resetpass">
    <div class="resetpass-container">
      <div class="resetpass-content">
        <el-form ref="loginForm" class="resetpass-form" label-position="left">
          <div class="title-wrap">
            <h3 class="title">Lupa Kata Sandi</h3>
          </div>
          <el-form-item prop="email">
            <span class="svg-container">
              <svg-icon icon-class="user" />
            </span>
            <!-- <label for="email">Email</label> -->
            <el-input name="email" type="text" placeholder="Email" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" style="width:100%;">
              Lanjutkan
            </el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Forgot',
};
</script>

<style rel="stylesheet/scss" lang="scss">
$bg:#2d3a4b;
$light_gray:#5F6368;

/* reset element-ui css */
.resetpass-container {
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

.resetpass {
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

  .resetpass-container {
    text-align: center;
    margin: auto;
    grid-template-columns: auto 480px;
    transition: all .3s ease-in-out;
    transform: scale(1);
    .el-button{
      background-color: #FFA84C;
    }
    .resetpass-form {
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
        margin: 0px auto 10px auto;
        text-align: center;
        font-weight: bold;
      }
      .sub-heading {
        font-size: 14px;
        color: $textColor;
        padding-bottom: 15px;
      }
    }
  }
}
.el-row {
  margin-bottom: 5px;
  &:last-child {
    margin-bottom: 0;
  }
}

</style>
    <!-- <h3>Lupa Kata Sandi</h3>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" placeholder="Email">
    </div>
    <button>Submit</button> -->

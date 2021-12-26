<template>
  <div>
    <el-card class="box-card" style="min-height:395px;">
      <div slot="header" class="clearfix">
        <span>Profil</span>
      </div>
      <el-skeleton v-if="isLoading" :rows="10" animated />
      <template v-else-if="user !== null">
        <div class="user-image">
          <el-row>
            <el-col :span="8">
              <img :src="user.avatar" style="width: 150px; height:150px; background: #eee; border-radius: 500px;">
            </el-col>
            <el-col :span="16" style="padding-top: 2em;">
              <div style="font-size:1.5em; font-weight:bold; border-bottom: 1px solid #999;">{{ user.name }}</div>
              <div style="margin-top:1em;">{{ getRole() }}</div>
            </el-col>
          </el-row>
        </div>
      </template>
    </el-card>
  </div>
</template>
<script>
export default {
  name: 'UserInformation',
  props: {
    user: {
      type: Object,
      default: null,
    },
    avatar: {
      type: String,
      default: '',
    },
    isLoading: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    getRole(){
      const roles = ['examiner', 'examiner-substance', 'examiner-administration'];
      const label = ['Ahli', 'Validator Substansi', 'Validator Administrasi'];
      const i = roles.indexOf(this.user.roles[0]);
      if (i >= 0){
        return label[i];
      }
      return '';
    },
  },
};
</script>

<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Tentang Penyusun</span>
      </div>
      <template v-if="user !== null">
        <div class="user-detail">
          <!-- picture -->
          <div class="user-picture">
            <img>
          </div>
          <el-row>
            <span class="label">Nama Penyusn</span>
            <span class="value">{{ user.name }}</span>
          </el-row>
          <el-row>
            <span class="label">Nama LPJP</span>
            <span class="value">{{ user.name }}</span>
          </el-row>

        </div>
      </template>
      <el-skeleton v-if="isLoading" rows="10" animated />
    </el-card>
  </div>
</template>
<script>
import Resource from '@/api/resource';

export default {
  name: 'FormulatorInformation',
  data() {
    return {
      isLoading: true,
      user: null,
      roles: [
        { name: 'formulator', label: 'Penyusun' },
      ],
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted() {
    // console.log('user-info: ', this.$store.getters.userId);
    this.getUser();
  },
  methods: {
    async getUser(){
      this.$store.dispatch('user/getInfo').then((response) => {
        let resource = null;
        if (this.isFormulator) {
          resource = new Resource('formulatorsByEmail');
        } else {
          resource = new Resource('initiatorsByEmail');
        }

        resource.list({ email: response.email }).then((res) => {
          this.user = res;
        });

        this.isLoading = false;
      }).catch((error) => {
        this.$message({
          message: error.message,
          type: 'error',
          duration: 5 * 1000,
        });
        console.log(error);
      });
      console.log('getUser: ', this.user);
    },
    getLabel(){
      if (this.isFormulator) {
        return 'Penyusun';
      }
      return 'Pemrakarsa';
    },
  },
};
</script>
<style scoped>

div.user-detail{
  padding: 3em;
}

</style>

<template>
  <div class="user-dashboard">
    <el-row :gutter="20">
      <el-col v-if="isFormulator" :span="12"><formulator-information :user="user" :is-loading="isLoading" /></el-col>
      <el-col v-else-if="isInitiator" :span="12"><initiator-information :user="user" :is-loading="isLoading" /></el-col>
      <el-col :span="12">
        <user-activities :user="user" />
        <user-summary :user="user" />
      </el-col>
    </el-row>
  </div>
</template>
<script>
import UserActivities from './components/activities';
import InitiatorInformation from './components/initiator';
import FormulatorInformation from './components/formulator';
import UserSummary from './components/summary';

import Resource from '@/api/resource';

export default {
  name: 'UserDashboard',
  components: { InitiatorInformation, FormulatorInformation, UserActivities, UserSummary },
  data() {
    return {
      user: null,
      avatar: '',
    };
  },
  computed: {
    isFormulator(){
      return this.$store.getters.roles.includes('formulator');
    },
    isInitiator(){
      return this.$store.getters.roles.includes('initiator');
    },
  },
  mounted() {
    this.getUser();
  },
  methods: {
    async getUser(){
      this.$store.dispatch('user/getInfo').then((response) => {
        this.avatar = response.avatar;
        let resource = null;
        if (this.isFormulator) {
          resource = new Resource('formulatorsByEmail');
        } else {
          resource = new Resource('initiatorsByEmail');
        }
        resource.list({ email: response.email }).then((res) => {
          this.user = res;
        });

        /* get */

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
  },
};
</script>
<style lang="scss">

div.user-dashboard {
    padding: 2em;
}

div.user-dashboard div.el-card {

    margin-bottom: 1.5em;
}

div.user-dashboard div.el-card__header{
    background: #afc7af;
    font-weight: 700;
    font-size: 90%;
}
.user-image{
  padding: 1em 2em;
}
.user-detail {
  padding: 1em 2em;

  .el-row{
    margin: 1em 0 2em;
  }

  span.label, span.value{
    display:block;
    line-height: 150%;
  }

  span.label {
    font-weight:bold;
    text-decoration: underline;
  }
}

</style>

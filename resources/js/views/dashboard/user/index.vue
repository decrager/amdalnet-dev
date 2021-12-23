<template>
  <div class="user-dashboard">
    <el-row :gutter="20">
      <el-col v-if="isFormulator" :span="12"><formulator-information /></el-col>
      <el-col v-else-if="isInitiator" :span="12"><initiator-information /></el-col>
      <el-col :span="12">
        <user-activities />
        <user-summary />
      </el-col>
    </el-row>
  </div>
</template>
<script>
import UserActivities from './components/activities';
import InitiatorInformation from './components/initiator';
import FormulatorInformation from './components/formulator';
import UserSummary from './components/summary';
export default {
  name: 'UserDashboard',
  components: { InitiatorInformation, FormulatorInformation, UserActivities, UserSummary },
  computed: {
    isFormulator(){
      return this.$store.getters.roles.includes('formulator');
    },
    isInitiator(){
      return this.$store.getters.roles.includes('initiator');
    },
  },
};
</script>
<style>

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

</style>

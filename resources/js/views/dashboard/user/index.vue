<template>
  <div class="user-dashboard">
    <el-row :gutter="20">
      <el-col :span="12"><user-information /></el-col>
      <el-col :span="12">
        <user-activities />
        <user-summary />
      </el-col>
    </el-row>
  </div>
</template>
<script>
import UserActivities from './components/activities';
import UserInformation from './components/infomation';
import UserSummary from './components/summary';
export default {
  name: 'UserDashboard',
  components: { UserInformation, UserActivities, UserSummary },
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
    text-decoration: underline;
}

</style>

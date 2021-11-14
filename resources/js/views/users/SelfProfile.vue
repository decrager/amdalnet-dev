<template>
  <div class="app-container">
    <el-form v-if="user" :model="user">
      <el-row :gutter="20">
        <el-col :span="6">
          <!-- <user-card :user="user" /> -->
          <user-detail-card v-if="user" :user="user" />
          <!-- <user-bio /> -->
        </el-col>
        <el-col :span="18">
          <user-activity :user="user" />
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
// import UserBio from './components/UserBio';
// import UserCard from './components/UserCard';
import UserDetailCard from './components/UserDetailCard';
import UserActivity from './components/UserActivity';
const initiatorResource = new Resource('initiatorsByEmail');
const formulatorResource = new Resource('formulatorsByEmail');
const lpjpResource = new Resource('lpjpsByEmail');
const expertBankResource = new Resource('expertByEmail');

export default {
  name: 'SelfProfile',
  components: { UserActivity, UserDetailCard },
  data() {
    return {
      user: {},
    };
  },
  watch: {
    '$route': 'getUser',
  },
  created() {
    this.getUser();
  },
  methods: {
    async getUser() {
      const data = await this.$store.dispatch('user/getInfo');
      data.initiatorData = await initiatorResource.list({ email: data.email });
      data.formulatorData = await formulatorResource.list({ email: data.email });
      data.lpjpData = await lpjpResource.list({ email: data.email });
      data.expertData = await expertBankResource.list({ email: data.email });
      this.user = data;

      console.log(this.user);
    },
  },
};
</script>

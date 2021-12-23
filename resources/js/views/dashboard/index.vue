<template>
  <div class="dashboard-container">
    <!-- <component :is="currentRole" /> -->
    <admin-dashboard v-if="isAdmin" />
    <user-dashboard v-else />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
// import adminDashboard from './admin';
// import editorDashboard from './editor';
import UserDashboard from './user';
import AdminDashboard from './admin';

export default {
  name: 'Dashboard',
  // components: { adminDashboard, editorDashboard },
  components: { UserDashboard, AdminDashboard },
  data() {
    return {
      currentRole: 'adminDashboard',
    };
  },
  computed: {
    ...mapGetters([
      'roles',
    ]),
    isAdmin(){
      return this.$store.getters.roles.includes('admin');
    },
  },
  created() {
    // if (!this.roles.includes('admin')) {
    //   this.currentRole = 'editorDashboard';
    // }
  },
};
</script>

<template>
  <div class="dashboard-container">
    <!-- <component :is="currentRole" /> -->
    <dashboard-admin v-if="isAdmin" />
    <user-dashboard v-else />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
// import adminDashboard from './admin';
// import editorDashboard from './editor';
import UserDashboard from './user';
import DashboardAdmin from './admin';

export default {
  name: 'Dashboard',
  // components: { adminDashboard, editorDashboard },
  components: { UserDashboard, DashboardAdmin },
  data() {
    return {
      currentRole: 'adminDashboard',
    };
  },
  computed: {
    ...mapGetters([
      'roles',
    ]),
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
    isInitiator() {
      return this.$store.getters.roles.includes('initiator');
    },
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

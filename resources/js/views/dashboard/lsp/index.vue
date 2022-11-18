<template>
  <div class="user-dashboard">
    <div v-if="isLSP">
      <el-row :gutter="20">
        <el-col :span="12">
          <lsp-information
            v-if="isLSP && user"
            :user="user"
            :is-loading="isLoading"
            :avatar="avatar"
          />
        </el-col>
        <el-col :span="12">
          <lsp-summary v-if="isLSP" :user="user" />
          <div v-else>
            <user-summary :user="user" />
          </div>
        </el-col>
      </el-row>
      <el-row v-if="isLSP && user">
        <lsp-formulators :user="user" />
      </el-row>
    </div>
  </div>
</template>
<script>
import { mapGetters } from 'vuex';
import UserSummary from './components/summary';
import Resource from '@/api/resource';
import LspInformation from './components/LspInformation.vue';
import LspFormulators from './components/LspFormulators.vue';
import LspSummary from './components/LspSummary.vue';

export default {
  name: 'UserDashboard',
  components: {
    UserSummary,
    LspInformation,
    LspFormulators,
    LspSummary,
  },
  data() {
    return {
      user: null,
      avatar: '',
      isLoading: true,
      isPemerintah: false,
    };
  },
  computed: {
    ...mapGetters({
      userLogged: 'user',
    }),
    isLSP() {
      return this.$store.getters.roles.includes('lsp');
    },
  },
  mounted() {
    if (this.isAllowed) {
      console.log('entering user dashboard...');
      this.getUser();
    }
  },
  methods: {
    async getUser() {
      console.log({ getUser: this.userLogged.email });
      if (this.userLogged) {
        this.avatar = this.userLogged.avatar;
        let resource = null;
        if (this.isLSP) {
          resource = new Resource('lspsByEmail');
        }
        if (!resource) {
          this.isLoading = false;
          return;
        }
        resource.list({ email: this.userLogged.email }).then((res) => {
          this.user = res;
          console.log({ res: res });
        });
        this.isLoading = false;
      }
    },
    isAllowed() {
      console.log('isAllowed?', this.$store.getters.roles);
      return !!(this.isFormulator || this.isInitiator || this.isExaminer);
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

div.user-dashboard div.el-card__header {
  background: #afc7af;
  font-weight: 700;
  font-size: 90%;
}
.user-image {
  padding: 1em 2em;
}
.user-detail {
  padding: 1em 2em;

  .el-row {
    margin: 1em 0 2em;
  }

  span.label,
  span.value {
    display: block;
    line-height: 150%;
  }

  span.label {
    font-weight: bold;
    text-decoration: underline;
  }
}
</style>

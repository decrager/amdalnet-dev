<template>
  <el-dropdown trigger="click" class="international" @command="handleSetLanguage">
    <div>
      <el-button style="background-color: white; color: grey">
        {{ longLanguage }}<i class="el-icon-arrow-down el-icon--right" />
      </el-button>
    </div>
    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item :disabled="language==='en'" command="en">
        English
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='id'" command="id">
        Bahasa Indonesia
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='ru'" command="ru">
        Русский
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  computed: {
    language() {
      return this.$store.getters.language;
    },
    longLanguage(){
      switch (this.$store.getters.language) {
        case 'id':
          return 'Bahasa (Indonesia)';
        default:
          return 'English';
      }
    },
  },
  methods: {
    handleSetLanguage(lang) {
      this.$i18n.locale = lang;
      this.$store.dispatch('app/setLanguage', lang);
      this.$message({
        message: 'Switch Language Success',
        type: 'success',
      });
    },
  },
};
</script>

<style scoped>
.international-icon {
  font-size: 20px;
  cursor: pointer;
  vertical-align: -5px!important;
}
</style>


<template>
  <div class="app-container">
    <identifikasi-dampak-table
      :id-project="idProject"
      :table="'metode-studi'"
      @handleSetData="handleSetData"
    />
    <div style="text-align: right; margin: 2em 0 1em 0;">
      <el-button
        v-if="!isAndal && isFormulator"
        type="success"
        size="small"
        icon="el-icon-check"
        @click="handleSaveForm()"
      >
        Simpan Perubahan
      </el-button>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import IdentifikasiDampakTable from './tables/IdentifikasiDampakTable.vue';
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'MetodeStudi',
  components: { IdentifikasiDampakTable },
  data() {
    return {
      data: [],
      idProject: 0,
    };
  },
  computed: {
    isAndal() {
      return this.$route.name === 'penyusunanAndal';
    },
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
  },
  methods: {
    handleSetData(data) {
      this.data = data;
    },
    handleSaveForm() {
      impactIdtResource
        .store({
          study_data: this.data,
        })
        .then((response) => {
          var message = (response.code === 200) ? 'Metode Studi berhasil disimpan' : 'Terjadi kesalahan pada server';
          var message_type = (response.code === 200) ? 'success' : 'error';
          this.$message({
            message: message,
            type: message_type,
            duration: 5 * 1000,
          });
          if (response.code === 200) {
            this.$emit('handleEnableSubmitForm');
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>


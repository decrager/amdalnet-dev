<template>
  <div class="app-container">
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      style="margin-bottom: 10px;"
      @click="handleSaveForm()"
    >
      Simpan Perubahan
    </el-button>
    <identifikasi-dampak-table
      :id-project="idProject"
      :table="'dampak-penting-hipotetik'"
      @handleSetData="handleSetData"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import IdentifikasiDampakTable from './IdentifikasiDampakTable.vue';
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'DampakPentingHipotetik',
  components: { IdentifikasiDampakTable },
  data() {
    return {
      idProject: 0,
      data: [],
    };
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
          var message = (response.code === 200) ? 'Dampak Penting Hipotetik berhasil disimpan' : 'Terjadi kesalahan pada server';
          var message_type = (response.code === 200) ? 'success' : 'error';
          this.$message({
            message: message,
            type: message_type,
            duration: 5 * 1000,
          });
          if (response.code === 200){
            this.$emit('handleReloadVsaList', 5);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

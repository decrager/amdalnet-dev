<template>
  <div class="app-container">
    <el-button
      v-if="!isAndal"
      :disabled="readonly"
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
      :table="'metode-studi'"
      @handleSetData="handleSetData"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import IdentifikasiDampakTable from './tables/IdentifikasiDampakTable.vue';
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'MetodeStudi',
  components: { IdentifikasiDampakTable },
  props: {
    readonly: {
      type: Boolean,
      default: () => false,
    },
  },
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

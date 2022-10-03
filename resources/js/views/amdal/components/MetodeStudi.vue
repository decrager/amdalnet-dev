<template>
  <div class="app-container">
    <identifikasi-dampak-table
      :id-project="idProject"
      :table="'metode-studi'"
      @handleSetData="handleSetData"
    />
    <div style="text-align: right; margin: 2em 0 1em 0;">
      <el-button
        v-if="isFormulator"
        type="success"
        size="small"
        icon="el-icon-check"
        :disabled="isReadOnly"
        @click="!isReadOnly && handleSaveForm()"
      >
        Simpan Perubahan
      </el-button>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
import IdentifikasiDampakTable from './tables/IdentifikasiDampakTable.vue';

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
    ...mapGetters({
      markingStatus: 'markingStatus',
    }),

    isReadOnly() {
      return this.markingStatus === 'amdal.form-ka-submitted' || this.markingStatus === 'announcement' || this.markingStatus === 'amdal.rklrpl-drafting';
    },

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
      const impactIdtResource = this.isAndal ? new Resource('andal-clone') : new Resource('impact-identifications');
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


<template>
  <div>
    <h5>Daftar Undangan</h5>
    <el-table
      v-loading="loadingtuk"
      :data="invitations"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    >
      <el-table-column width="30px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Peran">
        <template slot-scope="scope">
          <span v-if="scope.row.type == 'tuk'">{{ scope.row.role }} TUK</span>
          <el-select
            v-else
            v-model="scope.row.role"
            placeholder="Pilih Peran"
            style="width: 100%"
          >
            <el-option
              v-for="item in peran"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>

      <el-table-column label="Nama Anggota">
        <template slot-scope="scope">
          <span v-if="scope.row.type == 'tuk'">{{ scope.row.name }} TUK</span>
          <el-input v-else v-model="scope.row.name" />
        </template>
      </el-table-column>

      <el-table-column label="E-mail">
        <template slot-scope="scope">
          <div class="email-column">
            <span v-if="scope.row.type == 'tuk'">{{ scope.row.email }}</span>
            <el-input v-else v-model="scope.row.email" />
            <el-button
              type="text"
              icon="el-icon-close"
              style="display: block"
              @click.prevent="deleteRow(scope.row.id, scope.row.type)"
            />
          </div>
        </template>
      </el-table-column>
    </el-table>
    <div style="margin-top: 10px">
      <el-button icon="el-icon-plus" circle @click.prevent="addTableRow" />
    </div>
    <div style="margin-top: 13px">
      <h5>Ringkasan Rekomendasi Kelayakan dari Ahli</h5>
      <Tinymce v-model="reports.notes" :height="200" />
    </div>
    <el-upload
      :auto-upload="false"
      :on-change="handleUploadChange"
      :show-file-list="false"
      action=""
      style="text-align: right"
    >
      <el-button
        :loading="loadingUpload"
        type="warning"
        style="margin-top: 10px"
      >
        Unggah BA Final
      </el-button>
    </el-upload>
    <small
      v-if="errors.dokumen_file"
      style="color: #f56c6c; display: block; text-align: right; margin-top: 5px"
    >
      <span v-for="(error, index) in errors.dokumen_file" :key="index">
        {{ error }}
      </span>
    </small>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
const meetingReportResource = new Resource('meet-report-rkl-rpl');

export default {
  name: 'DaftarHadir',
  components: {
    Tinymce,
  },
  props: {
    invitations: {
      type: Array,
      default: () => [],
    },
    reports: {
      type: Object,
      default: () => {},
    },
    loadingtuk: Boolean,
  },
  data() {
    return {
      peran: [
        {
          label: 'Tenaga Ahli',
          value: 'Tenaga Ahli',
        },
        {
          label: 'Masyarakat',
          value: 'Masyarakat',
        },
      ],
      errors: {},
      loadingUpload: false,
    };
  },
  methods: {
    addTableRow() {
      this.invitations.push({
        id: Math.random(),
        role: null,
        name: null,
        email: null,
        type: 'other',
      });
    },
    deleteRow(id, personType) {
      this.$emit('deleteinvitation', { id, personType });
    },
    async handleUploadChange(file, fileList) {
      this.loadingUpload = true;
      const formData = new FormData();
      formData.append('idProject', this.$route.params.id);
      formData.append('dokumen_file', file.raw);
      formData.append('file', 'true');
      const isError = await meetingReportResource.store(formData);
      this.errors = isError.errors === null ? {} : isError.errors;
      this.loadingUpload = false;
      if (isError.errors === null) {
        this.$message({
          message: 'BA Final sukses diupload',
          type: 'success',
          duration: 5 * 1000,
        });
      }
    },
  },
};
</script>

<style scoped>
.email-column {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>

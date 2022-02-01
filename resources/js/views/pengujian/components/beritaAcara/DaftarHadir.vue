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
            @change="handleChangeRole($event, scope.$index)"
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

      <el-table-column label="Instansi">
        <template slot-scope="scope">
          <span v-if="scope.row.type == 'tuk'">{{
            scope.row.institution
          }}</span>
          <el-select
            v-else-if="isGovernmentInstitution(scope.row.role)"
            v-model="scope.row.id_government_institution"
            placeholder="Pilih Instansi"
            style="width: 100%"
            filterable
          >
            <el-option
              v-for="item in scope.row.institution_options"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
          <el-input v-else v-model="scope.row.institution" />
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
      <h5>Ringkasan Masukan dan Komentar</h5>
      <Tinymce v-model="reports.notes" v-loading="loadingtuk" :height="200" />
    </div>
    <el-upload
      v-if="!reports.file"
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
    <div v-else style="text-align: right">
      <el-button
        type="text"
        size="medium"
        icon="el-icon-download"
        style="color: blue"
        @click.prevent="download(reports.file)"
      >
        {{ baFileName }}
      </el-button>
    </div>
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
const meetingReportResource = new Resource('meeting-report');

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
    governmentinstitutions: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      peran: [
        {
          label: 'Kementerian',
          value: 'Kementerian',
        },
        {
          label: 'Lembaga',
          value: 'Lembaga',
        },
        {
          label: 'Pemerintah Provinsi',
          value: 'Pemerintah Provinsi',
        },
        {
          label: 'Pemerintah Kabupaten/Kota',
          value: 'Pemerintah Kabupaten/Kota',
        },
        {
          label: 'Tenaga Ahli',
          value: 'Tenaga Ahli',
        },
        {
          label: 'Masyarakat',
          value: 'Masyarakat',
        },
        {
          label: 'Lainnya',
          value: 'Lainnya',
        },
      ],
      errors: {},
      loadingUpload: false,
    };
  },
  computed: {
    baFileName() {
      const arrName = this.reports.file.split('/');
      return arrName[arrName.length - 1];
    },
  },
  methods: {
    addTableRow() {
      this.invitations.push({
        id: Math.random(),
        role: null,
        name: null,
        email: null,
        type: 'other',
        id_government_institution: null,
        institution_options: [],
        institution: null,
      });
    },
    isGovernmentInstitution(role) {
      return (
        role === 'Kementerian' ||
        role === 'Lembaga' ||
        role === 'Pemerintah Provinsi' ||
        role === 'Pemerintah Kabupaten/Kota'
      );
    },
    handleChangeRole(val, idx) {
      this.$emit('handleChangeRole', { val, idx });
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
      const data = await meetingReportResource.store(formData);
      this.errors = data.errors === null ? {} : data.errors;
      this.loadingUpload = false;
      if (data.errors === null) {
        this.$emit('updateuploadfile', { name: data.name });
        this.$message({
          message: 'BA Final sukses diupload',
          type: 'success',
          duration: 5 * 1000,
        });
      }
    },
    download(url) {
      window.open(url, '_blank').focus();
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

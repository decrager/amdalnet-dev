<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 + page * limit - limit }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama">
      <template slot-scope="scope">
        <el-input
          v-if="editId === scope.row.id"
          v-model="currentExpert.name"
          placeholder="Nama Tenaga Ahli"
        />
        <span v-else>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Status Peserta">
      <template slot-scope="scope">
        <el-select
          v-if="editId === scope.row.id"
          v-model="currentExpert.status"
          placeholder="Status Peserta"
          style="width: 100%"
        >
          <el-option
            v-for="item in status"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
        <span v-else>{{ scope.row.status }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Keahlian">
      <template slot-scope="scope">
        <el-input
          v-if="editId === scope.row.id"
          v-model="currentExpert.expertise"
          placeholder="Nama Tenaga Ahli"
        />
        <span v-else>{{ scope.row.expertise }}</span>
      </template>
    </el-table-column>

    <el-table-column label="File">
      <template slot-scope="scope">
        <el-upload
          v-if="editId === scope.row.id"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUploadChange"
          action="#"
          :show-file-list="false"
        >
          <el-button size="small" type="primary"> Click to upload </el-button>
          <span style="padding-left: 10px">{{
            fileName || currentExpert.cv
          }}</span>
        </el-upload>
        <el-button
          v-else
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.cv)"
        >
          CV
        </el-button>
      </template>
    </el-table-column>

    <el-table-column label="Aksi">
      <template slot-scope="scope">
        <template v-if="editId === scope.row.id">
          <el-button
            type="text"
            href="#"
            icon="el-icon-close"
            @click.prevent="handleCancel()"
          >
            Batal
          </el-button>
          <el-button
            type="text"
            href="#"
            icon="el-icon-check"
            @click.prevent="handleUpdate()"
          >
            Simpan
          </el-button>
        </template>
        <template v-else>
          <el-button
            type="text"
            href="#"
            icon="el-icon-edit"
            @click="handleEditForm(scope.row.id)"
          >
            Ubah
          </el-button>
          <el-button
            type="text"
            href="#"
            icon="el-icon-delete"
            @click.prevent="handleDelete(scope.row.id, scope.row.name)"
          >
            Hapus
          </el-button>
        </template>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'TeamMemberTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    page: {
      type: Number,
      default: () => 1,
    },
    limit: {
      type: Number,
      default: () => 10,
    },
  },
  data() {
    return {
      editId: null,
      currentExpert: {},
      status: [
        {
          value: 'Tenaga Ahli',
          label: 'Tenaga Ahli',
        },
        {
          value: 'Asisten',
          label: 'Asisten',
        },
      ],
      fileUpload: null,
      fileName: null,
    };
  },
  methods: {
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleEditForm(id) {
      this.editId = id;
      this.currentExpert = Array.from(this.list).find((exp) => exp.id === id);
      this.currentExpert.cv = null;
    },
    handleDelete(id, name) {
      this.$emit('handleDelete', { id, name });
    },
    handleUploadChange(file, fileList) {
      this.fileName = file.name;
      this.fileUpload = file.raw;
    },
    handleUpdate() {
      this.currentExpert.cv = this.fileUpload;
      this.$emit('handleUpdate', this.currentExpert);
      this.editId = null;
      this.currentExpert = {};
    },
    handleCancel() {
      this.editId = null;
      this.currentExpert = {};
    },
  },
};
</script>

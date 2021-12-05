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
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama">
      <template slot-scope="scope">
        <el-input
          v-model="list[scope.$index].name"
          placeholder="Nama Tenaga Ahli"
        />
        <!-- <span v-else>{{ scope.row.name }}</span> -->
      </template>
    </el-table-column>

    <el-table-column label="Posisi">
      <template slot-scope="scope">
        <el-select
          v-model="list[scope.$index].status"
          placeholder="Posisi"
          style="width: 100%"
        >
          <el-option
            v-for="item in status"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
        <!-- <span v-else>{{ scope.row.status }}</span> -->
      </template>
    </el-table-column>

    <el-table-column label="Keahlian">
      <template slot-scope="scope">
        <el-input
          v-model="list[scope.$index].expertise"
          placeholder="Keahlian"
        />
        <!-- <span v-else>{{ scope.row.expertise }}</span> -->
      </template>
    </el-table-column>

    <el-table-column label="File">
      <template slot-scope="scope">
        <el-upload
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUploadChange"
          action="#"
          :show-file-list="false"
        >
          <el-button size="small" type="primary"> Click to upload </el-button>
          <span style="padding-left: 10px">{{ fileName }}</span>
        </el-upload>
        <el-button
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

    <el-table-column label="" width="80px">
      <template slot-scope="scope">
        <el-button
          type="danger"
          size="mini"
          href="#"
          icon="el-icon-close"
          @click.prevent="handleDelete(scope.row.id, scope.row.name)"
        />
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

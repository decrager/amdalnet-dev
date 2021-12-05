<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
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
          v-if="teamtype === 'mandiri'"
          v-model="list[scope.$index].name"
          placeholder="Nama Tenaga Ahli"
        />
        <span v-else>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Posisi">
      <template slot-scope="scope">
        <el-select
          v-if="teamtype === 'mandiri'"
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
        <span v-else>{{ scope.row.status }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Keahlian">
      <template slot-scope="scope">
        <el-input
          v-if="teamtype === 'mandiri'"
          v-model="list[scope.$index].expertise"
          placeholder="Keahlian"
        />
        <span v-else>{{ scope.row.expertise }}</span>
      </template>
    </el-table-column>

    <el-table-column label="File">
      <template slot-scope="scope">
        <el-upload
          v-if="teamtype === 'mandiri'"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUploadChange"
          action="#"
          :show-file-list="false"
        >
          <el-button
            type="text"
            size="medium"
            icon="el-icon-upload"
            @click="fileNum = scope.row.num"
          >
            Upload
          </el-button>
          <span v-if="teamtype === 'mandiri'" style="padding-left: 10px">{{
            list[scope.$index].file_name
          }}</span>
        </el-upload>
        <el-button
          v-if="scope.row.cv && scope.row.type === 'update'"
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

    <el-table-column v-if="teamtype === 'mandiri'" label="" width="80px">
      <template slot-scope="scope">
        <el-button
          type="danger"
          size="mini"
          href="#"
          icon="el-icon-close"
          @click.prevent="handleDelete(scope.row.type, scope.row.num)"
        />
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'TeamAhliTable',
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
    teamtype: {
      type: String,
      default: () => '',
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
      fileNum: null,
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
    handleDelete(typeMember, num) {
      this.$emit('handleDelete', { typeMember, num });
    },
    handleUploadChange(file, fileList) {
      this.fileUpload = file.raw;
      const idx = this.list.findIndex((lis) => lis.num === this.fileNum);
      this.list[idx].cv = file.raw;
      this.list[idx].file_name = file.name;
    },
  },
};
</script>

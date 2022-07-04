<template>
  <el-table
    v-loading="loading || loadingAhli"
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
        <el-select
          v-if="teamtype === 'mandiri'"
          v-model="list[scope.$index].id_formulator"
          filterable
          placeholder="Pilih Tenaga Ahli"
          style="width: 100%"
          @change="handleChangeName($event, scope.$index)"
        >
          <el-option
            v-for="item in ahli"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <span v-else>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Posisi">
      <template slot-scope="scope">
        <el-select
          v-if="teamtype === 'mandiri'"
          v-model="list[scope.$index].position"
          placeholder="Posisi"
          style="width: 100%"
        >
          <el-option
            v-for="item in position"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
        <span v-else>{{ scope.row.position }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Keahlian">
      <template slot-scope="scope">
        <span>{{ scope.row.expertise }}</span>
      </template>
    </el-table-column>

    <el-table-column label="File">
      <template slot-scope="scope">
        <el-button
          v-if="scope.row.cv_file"
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.cv_file)"
        >
          CV
        </el-button>
        <span v-else>-</span>
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
import Resource from '@/api/resource';
const formulatorTeamsResource = new Resource('formulator-teams');

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
      position: [
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
      ahli: [],
      loadingAhli: false,
    };
  },
  created() {
    this.getTenagaAhli();
  },
  methods: {
    download(url) {
      window.open(url, '_blank').focus();
    },
    async getTenagaAhli() {
      this.loadingAhli = true;
      this.ahli = await formulatorTeamsResource.list({
        type: 'tenaga-ahli',
        idProject: this.$route.params.id,
      });
      this.loadingAhli = false;
    },
    handleChangeName(id, idx) {
      const check = this.list.filter((x) => x.id_formulator === id);
      if (check.length > 1) {
        this.$alert('Tenaga Ahli Tersebut Sudah Ditambahkan', '', {
          center: true,
        });
        this.list[idx].id_formulator = null;
        return false;
      }

      const formulator = this.ahli.find((x) => x.id === id);
      this.list[idx].expertise = formulator.expertise;
      this.list[idx].cv_file = formulator.cv_file;
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

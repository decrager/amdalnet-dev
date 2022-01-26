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
        <el-select
          v-model="list[scope.$index].id_member"
          placeholder="Pilih Nama"
          style="width: 100%"
          :filterable="true"
          @change="handleChangeMember($event, scope.$index)"
        >
          <el-option
            v-for="(item, idx) in expertemployee"
            :key="idx"
            :label="item.name"
            :value="`${item.id}-${item.type}`"
          />
        </el-select>
      </template>
    </el-table-column>

    <el-table-column label="NIK" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.nik }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Peran">
      <template slot-scope="scope">
        <el-select
          v-model="list[scope.$index].role"
          placeholder="Pilih Peran"
          style="width: 100%"
        >
          <el-option
            v-for="item in role"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>
    </el-table-column>

    <el-table-column label="Jabatan" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.position }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Instansi" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.institution }}</span>
      </template>
    </el-table-column>

    <el-table-column label="File" align="center">
      <template slot-scope="scope">
        <el-button
          v-if="scope.row.cv"
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

    <el-table-column label="Aksi" width="80px" align="center">
      <template slot-scope="scope">
        <el-button
          type="text"
          size="mini"
          href="#"
          icon="el-icon-delete"
          @click.prevent="handleDelete(scope.row.id, scope.row.num)"
        >
          Hapus
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'TimPenyusunTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    teamtype: {
      type: String,
      default: () => '',
    },
    expertemployee: {
      type: Array,
      default: () => [],
    },
    deletedtuk: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      role: [
        {
          value: 'Ketua',
          label: 'Ketua Tim Uji Kelayakan',
        },
        {
          value: 'Kepala Sekretariat',
          label: 'Kepala Sekretariat Tim Uji Kelayakan',
        },
        {
          value: 'Anggota',
          label: 'Anggota Tim Uji Kelayakan',
        },
      ],
    };
  },
  methods: {
    handleChangeMember(data, idx) {
      const idType = data.split('-');
      const indx = this.expertemployee.findIndex((ex) => {
        return ex.id === +idType[0] && ex.type === idType[1];
      });
      this.list[idx].type = this.expertemployee[indx].type;
      this.list[idx].nik = this.expertemployee[indx].nik;
      this.list[idx].position = this.expertemployee[indx].position;
      this.list[idx].institution = this.expertemployee[indx].institution;
      this.list[idx].cv = this.expertemployee[indx].cv;
      this.list[idx].email = this.expertemployee[indx].email;
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleDelete(id, num) {
      this.$emit('handleDelete', { id, num });
    },
  },
};
</script>

<template>
  <el-table
    :data="list"
    fit
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    @sort-change="onTableSort"
  >
    <el-table-column label="No." width="54px" align="center">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama" sortable="custom" prop="name">
      <template slot-scope="scope">
        <el-select
          v-if="scope.row.type === 'new'"
          v-model="list[scope.$index].id"
          placeholder="Pilih Penyusun"
          style="width: 100%"
          filterable
          @change="handleSelect($event, scope.$index)"
        >
          <el-option
            v-for="item in formulators"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <span v-else>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nomor Registrasi" align="center" sortable="custom" prop="reg_no">
      <template slot-scope="scope">
        <span>{{ scope.row.reg_no }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nomor Sertifikat" align="center" sortable="custom" prop="cert_no">
      <template slot-scope="scope">
        <span>{{ scope.row.cert_no }}</span>
      </template>
    </el-table-column>

    <el-table-column v-model="value" align="center" prop="membership_status" column-key="membership_status">
      <template slot="header">
        <el-select
          v-model="membershipStatusFilter "
          class="filter-header"
          clearable
          placeholder="Sertifikasi"
          @change="onMembershipStatusFilter"
        >
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>
      <template slot-scope="scope">
        <span>{{ scope.row.membership_status }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Status" align="center">
      <template slot-scope="scope">
        <span>{{
          calculateStatus(scope.row.date_start, scope.row.date_end)
        }}</span>
      </template>
    </el-table-column>

    <el-table-column label="File" align="center">
      <template slot-scope="scope">
        <el-button
          v-if="scope.row.cert_file"
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.cert_file)"
        >
          Sertifikat
        </el-button>
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
      </template>
    </el-table-column>

    <el-table-column label="Action" width="80px" align="center">
      <template slot-scope="scope">
        <el-button
          type="danger"
          size="mini"
          @click.prevent="
            handleDelete(scope.row.id, scope.row.type, scope.row.num)
          "
        >
          Hapus
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import Resource from '@/api/resource';
const lspResource = new Resource('lsp');

export default {
  name: 'MemberTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    options: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      formulators: [],
      loading: false,
      listQuery: [],
      MembershipStatusFilter: '',
    };
  },
  created() {
    this.getFormulators();
  },
  methods: {
    onTableSort(sort) {
      // console.log({ ontabelsor: 'ok' })
      const order = sort.order === 'ascending' ? 'asc' : 'desc';
      const prop = sort.prop;
      this.$emit('sort', { order: order, prop: prop });
      // this.handleFilter();
    },
    onMembershipStatusFilter(val) {
      // console.log('membershipStatus: ', val);
      this.$emit('membershipStatusFilter', val);
    },
    async getFormulators() {
      this.loading = true;
      this.formulators = await lspResource.list({
        formulators: 'true',
        idLsp: this.$route.params.id,
      });
      this.loading = false;
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleDelete(id, type, num) {
      this.$emit('handleDelete', { id, type, num });
    },
    isActive(date_end) {
      if (date_end) {
        if (new Date() < new Date(date_end)) {
          return 'Aktif';
        }
      }

      return 'Tidak Aktif';
    },
    handleSelect(id, idx) {
      const indx = this.formulators.findIndex((fo) => {
        return fo.id === id;
      });
      this.list[idx].name = this.formulators[indx].name;
      this.list[idx].reg_no = this.formulators[indx].reg_no;
      this.list[idx].cert_no = this.formulators[indx].cert_no;
      this.list[idx].membership_status =
        this.formulators[indx].membership_status;
      this.list[idx].cert_file = this.formulators[indx].cert_file;
      this.list[idx].cv_file = this.formulators[indx].cv_file;
      this.list[idx].date_start = this.formulators[indx].date_start;
      this.list[idx].date_end = this.formulators[indx].date_end;
    },
    calculateStatus(awal, akhir) {
      const tglAwal = new Date(awal);
      const tglAkhir = new Date(akhir);
      if (
        new Date().getTime() >= tglAwal.getTime() &&
        new Date().getTime() <= tglAkhir.getTime()
      ) {
        return 'Aktif';
      } else {
        return 'NonAktif';
      }
    },
  },
};
</script>

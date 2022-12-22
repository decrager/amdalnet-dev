<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    @sort-change="onTableSort"
  >
    <el-table-column align="center" label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 + page * limit - limit }}</span>
      </template>
    </el-table-column>

    <el-table-column
      align="center"
      label="Nomor Registrasi"
      prop="registration_no"
      sortable="custom"
    />
    <el-table-column
      align="center"
      label="Nama Rencana Usaha/Kegiatan"
      prop="project_title"
      sortable="custom"
    />
    <el-table-column
      align="center"
      label="Pemrakarsa"
      prop="initiator_name"
      sortable
    />
    <el-table-column
      align="center"
      label="Lokasi Kegiatan"
      prop="complete_address"
      sortable
    />
    <el-table-column
      align="center"
      label="Tahap"
      prop="marking_label"
    >
      <template slot="header">
        <el-select
          v-model="listQuery.marking_label"
          class="filter-header"
          clearable
          placeholder="Tahap"
          @change="onDocTypeFilter"
        >
          <el-option
            v-for="item in marking"
            :key="item.id"
            :label="item.public_tracking"
            :value="item.state"
          />
        </el-select>
      </template>
      <template slot-scope="scope">
        <span>{{ scope.row.marking_label }}</span>
      </template>
    </el-table-column>
    <el-table-column
      prop="updated_at"
      align="center"
      label="Tanggal"
      width="150px"
      sortable="custom"
    >
      <template slot-scope="scope">
        <div style="line-height: 1.1em;">
          <span>{{ scope.row.updated_at | parseTime('{y}-{m}-{d}') }}</span>
        </div>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Anggota Uji Kelayakan">
      <template slot-scope="scope">
        <el-button type="warning" @click="handleTukProjectMember(scope.row.id)">
          Ubah
        </el-button>
        <el-button type="warning" @click="handleViewForm(scope.row.id)">
          Lihat Detail Penapisan
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import Resource from '@/api/resource';
const tukProjectResource = new Resource('tuk-project');
export default {
  name: 'TukProjectTable',
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
    marking: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      listQuery: {
        page: 1,
        limit: 10,
        marking_label: null,
        orderBy: 'created_at',
        order: 'DESC',
      },
      total: 0,
    };
  },
  methods: {
    onTableSort(sort) {
      this.listQuery.orderBy = sort.prop;
      this.listQuery.order = (sort.order === 'ascending') ? 'ASC' : 'DESC';
      this.handleFilter();
    },
    handleFilter() {
      this.getFiltered(this.listQuery);
    },
    async getFiltered(query) {
      this.loading = true;
      const { data, total } = await tukProjectResource.list(query);
      this.list = data.map((x) => {
        x.initiator_name = x.initiator.name;
        x.complete_address = this.getAddress(x.address);
        return x;
      });
      this.total = total;
      this.loading = false;
    },
    getAddress(address) {
      if (address) {
        if (address.length > 0) {
          return `${address[0].address}, ${this.capitalize(
            address[0].district
          )}, Provinsi ${this.capitalize(address[0].prov)}`;
        }
      }

      return '';
    },
    capitalize(mySentence) {
      if (mySentence) {
        const words = mySentence.split(' ');
        const newWords = words
          .map((word) => {
            return (
              word.toLowerCase()[0].toUpperCase() +
              word.toLowerCase().substring(1)
            );
          })
          .join(' ');
        return newWords;
      } else {
        return '';
      }
    },
    onDocTypeFilter(value) {
      this.listQuery.marking_label = value;
      this.listQuery.page = 1;
      this.handleFilter();
    },
    handleTukProjectMember(id) {
      // eslint-disable-next-line object-curly-spacing
      this.$router.push({ name: 'tukProjectMember', params: { id } });
    },
    handleViewForm(id) {
      const mapped = this.list.map(e => {
        e.listSubProject = e.list_sub_project;
        return e;
      });
      const currentProject = mapped.find((item) => item.id === id);

      // change project_year to string
      currentProject.project_year = currentProject.project_year.toString();
      // change field to number and formulator team
      currentProject.field = Number(currentProject.field);
      currentProject.id_formulator_team = Number(currentProject.id_formulator_team);

      this.$router.push({
        name: 'publishProject',
        params: { project: currentProject, readonly: true },
      });
    },
  },
};
</script>

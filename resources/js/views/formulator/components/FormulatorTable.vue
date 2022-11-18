<!-- eslint-disable vue/html-indent -->
<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="Nama" prop="name" sortable />
    <el-table-column
      align="center"
      label="No. Registrasi"
      prop="reg_no"
    />
    <el-table-column
      align="center"
      label="No. Sertifikat"
      prop="cert_no"
      sortable
    />
    <el-table-column align="center" prop="membership_status" column-key="membership_status">
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
    </el-table-column>
    <el-table-column v-if="checkRole(['admin'])" align="center" prop="data_lsp.lsp_name" column-key="data_lsp.lsp_name">
      <template slot="header">
        <el-select
          v-model="lspFilter "
          class="filter-header"
          clearable
          placeholder="LSP"
          @change="onLspFilter"
        >
          <el-option
            v-for="item in lspOptions"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Status" prop="status" sortable>
      <template slot-scope="scope">
        <el-tag :type="scope.row.status | statusFilter">
          {{ scope.row.status }}
        </el-tag>
      </template>
    </el-table-column>

    <el-table-column label="File">
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
        <el-button
          v-if="
            certificate &&
            (checkPermission(['manage formulator']) || checkRole(['admin']))
          "
          type="warning"
          size="mini"
          @click="handleCertificate(scope.row.id)"
        >
          Update
        </el-button>
      </template>
    </el-table-column>

    <el-table-column
      v-if="
        !certificate &&
        (checkPermission(['manage formulator']) || checkRole(['admin']))
      "
      align="center"
      label="Aksi"
    >
      <template slot-scope="scope">
        <el-button
          type="text"
          href="#"
          icon="el-icon-delete"
          @click="handleDelete(scope.row.id, scope.row.name)"
        >
          Hapus
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import checkPermission from '@/utils/permission';
import checkRole from '@/utils/role';
import Resource from '@/api/resource';
const lspResource = new Resource('lsp');

export default {
  name: 'FormulatorTable',
  filters: {
    statusFilter(status) {
      const statusMap = {
        Aktif: 'success',
        NonAktif: 'danger',
      };
      return statusMap[status];
    },
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    options: {
      type: Array,
      default: () => [
        {
          value: 'ATPA',
          label: 'ATPA',
        },
        {
          value: 'KTPA',
          label: 'KTPA',
        },
      ],
    },
    loading: Boolean,
    certificate: Boolean,
  },
  data() {
    return {
      listQuery: [],
      lspOptions: [],
      membershipStatusFilter: '',
      lspFilter: '',
    };
  },
  mounted() {
    this.getLsp();
  },
  methods: {
    checkPermission,
    checkRole,
    async getLsp() {
      const { data } = await lspResource.list({
        options: 'true',
      });
      this.lspOptions = data.map((i) => {
        return { value: i.id, label: i.lsp_name };
      });
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    handleCertificate(id) {
      // eslint-disable-next-line object-curly-spacing
      this.$router.push({ name: 'certificateFormulator', params: { id } });
    },
    onMembershipStatusFilter(val) {
      // console.log('membershipStatus: ', val);
      this.$emit('membershipStatusFilter', val);
    },
    onLspFilter(val) {
      // console.log('membershipStatus: ', val);
      this.$emit('lspFilter', val);
    },
  },
};
</script>

<style scoped>
.expand-container {
  display: flex;
  justify-content: space-around;
}
.expand-container div {
  width: 50%;
  padding: 1rem 3rem;
}
.expand-container__right {
  text-align: right;
}
</style>

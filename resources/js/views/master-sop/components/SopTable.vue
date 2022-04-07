<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column type="expand" class="row-detail">
      <template slot-scope="scope">
        <div class="post">
          <div class="entity-block">
            <div>
              <span style="font-weight: bold">Bentuk Pengawasan :</span>
              <div v-html="scope.row.monitoring_form" />
            </div>
          </div>
          <div class="entity-block">
            <div>
              <span style="font-weight: bold">Waktu Pengawasan :</span>
              {{ scope.row.monitoring_time }}
            </div>
          </div>
          <div class="entity-block">
            <div>
              <span style="font-weight: bold">Frekwensi Pengawasan :</span>
              {{ scope.row.monitoring_freq }}
            </div>
          </div>
          <div class="entity-block">
            <div>
              <span style="font-weight: bold">Nama :</span> {{ scope.row.name }}
            </div>
          </div>
          <div class="entity-block">
            <div>
              <span style="font-weight: bold">Dampak :</span>
              {{ scope.row.impact }}
            </div>
          </div>
          <div class="entity-block">
            <div>
              <span style="font-weight: bold">Dampak Lainya :</span>
              {{ scope.row.other_impact }}
            </div>
          </div>
          <span class="action">
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
              @click="handleDelete(scope.row.id, scope.row.name)"
            >
              Hapus
            </el-button>
          </span>
        </div>
      </template>
    </el-table-column>
    <el-table-column
      align="center"
      label="ID Komponen"
      prop="id_component"
      sortable
    />
    <el-table-column
      align="center"
      label="ID Rona Awal"
      prop="id_rona_awal"
      sortable
    />
    <el-table-column
      align="center"
      label="Bentuk Pengelolaan"
      prop="mgmt_form"
      sortable
    />
    <el-table-column
      align="center"
      label="Periode Pengelolaan"
      prop="mgmt_period"
      sortable
    />

    <!-- <el-table-column align="center" label="Bentuk Pengawasan">
      <template slot-scope="scope">
        <span>{{ scope.row.monitoring_form }}</span>
      </template>
    </el-table-column> -->

    <!-- <el-table-column align="center" label="Waktu Pengawasan">
      <template slot-scope="scope">
        <span>{{ scope.row.monitoring_time }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Frekwensi Pengawasan">
      <template slot-scope="scope">
        <span>{{ scope.row.monitoring_freq }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Dampak">
      <template slot-scope="scope">
        <span>{{ scope.row.impact }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Dampak Lainnya">
      <template slot-scope="scope">
        <span>{{ scope.row.other_impact }}</span>
      </template>
    </el-table-column>

     <el-table-column align="center" label="Aksi">
      <template slot-scope="scope">
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
          @click="handleDelete(scope.row.id, scope.row.name)"
        >
          Hapus
        </el-button>
      </template>
    </el-table-column> -->
  </el-table>
</template>

<script>
export default {
  name: 'SopTable',
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
    loading: Boolean,
  },
  methods: {
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
  },
};
</script>

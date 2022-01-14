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
    <div style="margin-top: 13px;">
      <h5>Ringkasan Rekomendasi Kelayakan dari Ahli</h5>
      <Tinymce v-model="reports.notes" :height="200" />
    </div>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';

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
  },
  data() {
    return {
      peran: [
        {
          label: 'Tenaga Ahli',
          value: 'Tenaga Ahli',
        },
        {
          label: 'Masyarakat',
          value: 'Masyarakat',
        },
      ],
    };
  },
  methods: {
    addTableRow() {
      this.invitations.push({
        id: Math.random(),
        role: null,
        name: null,
        email: null,
        type: 'other',
      });
    },
    deleteRow(id, personType) {
      this.$emit('deleteinvitation', { id, personType });
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

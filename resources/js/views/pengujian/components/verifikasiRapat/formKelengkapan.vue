<template>
  <div>
    <h4 align="center">
      FORM KELENGKAPAN INPUT DATA DIGITAL PENGAJUAN PERSETUJUAN LINGKUNGAN: KA
    </h4>
    <el-table
      v-loading="loadingverification"
      :data="list.ka_forms"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    >
      <el-table-column label="No." width="54px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Data">
        <template slot-scope="scope">
          <a :href="scope.row.link" download>{{ scope.row.name }}</a>
        </template>
      </el-table-column>

      <el-table-column label="Kesesuaian">
        <template slot-scope="scope">
          <el-select
            v-model="scope.row.suitability"
            placeholder="Pilih Kesesuaian"
            style="width: 100%"
          >
            <el-option
              v-for="sesuai in kesesuaian"
              :key="sesuai.value"
              :label="sesuai.label"
              :value="sesuai.value"
            />
          </el-select>
        </template>
      </el-table-column>

      <el-table-column label="Keterangan">
        <template slot-scope="scope">
          <el-input v-model="scope.row.description" type="textarea" />
        </template>
      </el-table-column>
    </el-table>
    <el-row :gutter="32" style="font-size: 13px; text-align: center">
      <el-col :sm="12" :md="12">
        <p class="mini-title">Disetujui Oleh,</p>
        <div>
          <el-input v-model="list.approved_by_position" />
        </div>
        <el-row :gutter="1" style="margin: 10px 0">
          <el-col :sm="12" :md="12">
            <el-input v-model="list.approved_in" />
          </el-col>
          <el-col :sm="12" :md="12">
            <p>, {{ list.approved_date }}</p>
          </el-col>
        </el-row>
        <el-input v-model="list.approved_by_name" />
      </el-col>
      <el-col :sm="12" :md="12">
        <p class="mini-title">Diperiksa oleh,</p>
        <p
          class="mini-title"
          style="padding-top: 0.5rem; padding-bottom: 0.5rem"
        >
          Penanggung Jawab Materi,
        </p>
        <el-row :gutter="1" style="margin: 10px 0">
          <el-col :sm="12" :md="12">
            <el-input v-model="list.checked_in" />
          </el-col>
          <el-col :sm="12" :md="12">
            <p>, {{ list.checked_date }}</p>
          </el-col>
        </el-row>
        <el-input v-model="list.checked_by" />
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'FormKelengkapan',
  props: {
    list: {
      type: Object,
      default: () => {},
    },
    loadingverification: Boolean,
  },
  data() {
    return {};
  },
  methods: {},
};
</script>

<style scoped>
.mini-title {
  font-size: 14px;
}
</style>

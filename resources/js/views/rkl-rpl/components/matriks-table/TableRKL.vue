<template>
  <div>
    <div class="filter-container">
      <el-button
        class="filter-item"
        type="primary"
        style="font-size: 0.8rem"
        @click="handleSubmit"
      >
        {{ 'Simpan Perubahan' }}
      </el-button>
      <span style="font-size: 0.8rem">
        <em>{{ lasttime }}</em>
      </span>
    </div>
    <el-table
      v-loading="loading"
      :data="list"
      fit
      border
      highlight-current-row
      :header-cell-style="{
        background: '#cdf4b5',
        color: 'black',
        textAlign: 'center',
        border: '0px',
      }"
      style="font-size: 12px"
    >
      <el-table-column label="Dampak Lingkungan yang Dikelola">
        <template slot-scope="scope">
          <span
            :class="{
              'row-title': Boolean(scope.row.type === 'title'),
            }"
          >
            {{ scope.row.name }}
          </span>
        </template>
      </el-table-column>

      <el-table-column label="Sumber Dampak">
        <template slot-scope="scope">
          <span>{{
            scope.row.impact_source ? scope.row.impact_source : ''
          }}</span>
        </template>
      </el-table-column>

      <el-table-column
        label="Indikator Keberhasilan Pengelolaan Lingkungan Hidup"
      >
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.success_indicator"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Pengelolaan Lingkungan Hidup">
        <el-table-column label="Bentuk Pengelolaan Lingkungan Hidup">
          <template slot-scope="scope">
            <el-input
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.form"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Lokasi Pengelolaan Lingkungan Hidup">
          <template slot-scope="scope">
            <el-input
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.location"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Periode Pengelolaan Lingkungan Hidup">
          <template slot-scope="scope">
            <el-input
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.period"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column label="Institusi Pengelolaan Lingkungan Hidup">
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.institution"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Keterangan">
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.type == 'subtitle'"
            v-model="scope.row.description"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  name: 'TableRKL',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    lasttime: {
      type: String,
      default: () => null,
    },
    loading: Boolean,
  },
  methods: {
    handleSubmit() {
      this.$emit('handleSubmit');
    },
  },
};
</script>

<style scoped>
.row-title {
  font-weight: bold;
}
</style>

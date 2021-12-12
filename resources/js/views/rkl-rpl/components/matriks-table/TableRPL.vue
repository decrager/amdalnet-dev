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
      <el-table-column label="Dampak Lingkungan yang Dipantau">
        <el-table-column label="Jenis Dampak yang Timbul">
          <template slot-scope="scope">
            <span
              :class="{
                'row-title': Boolean(scope.row.type == 'title'),
              }"
            >
              {{ scope.row.name }}
            </span>
          </template>
        </el-table-column>

        <el-table-column label="Indikator/Parameter">
          <template slot-scope="scope">
            <span>
              {{ scope.row.indicator ? scope.row.indicator : '' }}
            </span>
          </template>
        </el-table-column>

        <el-table-column label="Sumber Dampak">
          <template slot-scope="scope">
            <span>
              {{ scope.row.impact_source ? scope.row.impact_source : '' }}
            </span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column label="Bentuk Pemantauan Lingkungan Hidup">
        <el-table-column label="Metode Pengumpulan & Analisis Data">
          <template slot-scope="scope">
            <el-input
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.collection_method"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Lokasi Pantau">
          <template slot-scope="scope">
            <el-input
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.location"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Waktu & Frekuensi">
          <template slot-scope="scope">
            <el-input
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.time_frequent"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column label="Institusi Pemantauan Lingkungan Hidup">
        <el-table-column label="Pelaksana">
          <template slot-scope="scope">
            <el-select
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.executor"
              placeholder="Pilih Pelaksana"
              style="width: 100%"
            >
              <el-option
                v-for="item in institutions"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Pengawas">
          <template slot-scope="scope">
            <el-select
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.supervisor"
              placeholder="Pilih Pengawas"
              style="width: 100%"
            >
              <el-option
                v-for="item in institutions"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Penerima Laporan">
          <template slot-scope="scope">
            <el-select
              v-if="scope.row.type == 'subtitle'"
              v-model="scope.row.report_recipient"
              placeholder="Pilih Penerima Laporan"
              style="width: 100%"
            >
              <el-option
                v-for="item in institutions"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>
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
    institutions: {
      type: Array,
      default: () => [],
    },
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

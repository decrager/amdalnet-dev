<template>
  <el-card
    class="box-card"
    style="
      background: #afc7af;
      border: 1px solid #85ab85;
      margin: 2em auto 2.5em;
    "
  >
    <div id="date-filter">
      Periode&nbsp;&nbsp;
      <el-select
        v-model="periode"
        placeholder="Select"
        @change="handleChangePeriod"
      >
        <el-option
          v-for="item in options"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
      <div
        v-if="datePicker.type === 'yearrange'"
        style="width: 30%; display: inline-flex"
      >
        <el-input
          v-model="date_range[0]"
          type="number"
          placeholder="Tahun Awal"
        />
        <el-input
          v-model="date_range[1]"
          type="number"
          placeholder="Tahun Akhir"
        />
      </div>
      <el-date-picker
        v-else
        v-model="date_range"
        :type="datePicker.type"
        :start-placeholder="datePicker.startPlaceHolder"
        :end-placeholder="datePicker.endPlaceholder"
        :value-format="datePicker.valueFormat"
      />
      <el-button
        type="info"
        style="font-weight: bold; margin-left: 1em"
        round
        @click="goFilter"
      >
        &nbsp;Filter&nbsp;
      </el-button>
    </div>
  </el-card>
</template>
<script>
export default {
  name: 'DateFilter',
  data() {
    return {
      datePicker: {
        type: 'daterange',
        startPlaceHolder: 'Tanggal awal',
        endPlaceholder: 'Tanggal akhir',
        valueFormat: 'yyyy-MM-dd',
      },
      options: [
        { label: 'harian', value: 1 },
        { label: 'bulanan', value: 2 },
        { label: 'tahunan', value: 3 },
      ],
      periode: 1,
      date_range: [
        new Date(new Date().getFullYear(), new Date().getMonth(), 2)
          .toISOString()
          .split('T')[0],
        new Date(new Date().getFullYear(), new Date().getMonth() + 1, 1)
          .toISOString()
          .split('T')[0],
      ],
    };
  },
  methods: {
    goFilter() {
      console.log('date range', this.date_range);
      this.$emit('filterChange', {
        date_range: this.date_range,
        periode: this.periode,
      }).$nextTick(() => {
        this.periode = '';
        this.date_range = [];
      });
    },
    handleChangePeriod(val) {
      if (val === 1) {
        this.datePicker = {
          type: 'daterange',
          startPlaceHolder: 'Tanggal awal',
          endPlaceholder: 'Tanggal akhir',
          valueFormat: 'yyyy-MM-dd',
        };
      } else if (val === 2) {
        this.datePicker = {
          type: 'monthrange',
          startPlaceHolder: 'Bulan awal',
          endPlaceholder: 'Bulan akhir',
          valueFormat: 'yyyy-MM',
        };
      } else if (val === 3) {
        this.datePicker.type = 'yearrange';
      }
    },
  },
};
</script>
<style scoped>
#date-filter {
  font-weight: bold;
  font-size: 90%;
}
</style>

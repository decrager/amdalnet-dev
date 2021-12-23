<template>
  <el-card class="box-card" style="background: #AFC7AF; border:1px solid #85AB85; margin: 2em auto 2.5em;">
    <div id="date-filter">Periode&nbsp;&nbsp;
      <el-select v-model="value" placeholder="Select">
        <el-option
          v-for="item in options"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
      <el-date-picker
        v-model="value1"
        type="daterange"
        start-placeholder="Tanggal awal"
        end-placeholder="Tanggal akhir"
      />
      <el-button type="info" style="font-weight:bold;margin-left:1em;" round>&nbsp;Filter&nbsp;</el-button>
    </div>
  </el-card>
</template>
<script>
export default {
  name: 'DateFilter',
  data() {
    return {
      value: '',
    };
  },
};
</script>
<style scoped>

#date-filter {
  font-weight: bold;
  font-size: 90%;
}

</style>

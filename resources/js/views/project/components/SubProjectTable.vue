<template>
  <div>
    <el-table
      :key="refresh"
      v-loading="loading"
      :header-cell-style="{ background: '#099C4B', color: 'white' }"
      :data="list"
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="KBLI">
        <template slot-scope="scope">
          <el-select v-model="scope.row.kbli" filterable placeholder="Pilih" size="mini" @change="onChangeKbli(scope.row)">
            <el-option
              v-for="item in listKbli"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Nama Kegiatan">
        <template slot-scope="scope">
          <el-input
            v-model="scope.row.name"
            class="edit-input"
            size="mini"
          />
        </template>
      </el-table-column>

      <el-table-column align="center" label="Jenis Rencana Kegiatan">
        <template slot-scope="scope">
          <el-select v-model="scope.row.biz_type" filterable placeholder="Pilih" size="mini" @change="onChangeFieldType(scope.row)">
            <el-option
              v-for="item in scope.row.listField"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Skala Besaran">
        <template slot-scope="scope">
          <el-button type="primary" @click="handleClick(scope.row)">Masukkan</el-button>
          <param-dialog :show="scope.row.showParamDialog || false" :list="scope.row.listSubProjectParams" :refresh-dialog="refresh" :kbli="scope.row.kbli" @handleCancelParam="handleCancelParam(scope.row)" @handleRefreshDialog="handleRefreshDialog" />
        </template>
      </el-table-column>

      <el-table-column align="center" label="Kegiatan Utama/Pendukung">
        <template slot-scope="scope">
          <el-select v-model="scope.row.type" filterable placeholder="Pilih" size="mini">
            <el-option
              v-for="item in typeOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import ParamDialog from '@/views/project/components/ParamDialog.vue';
const kbliEnvParamResource = new Resource('kbli-env-params');
const kbliResource = new Resource('kblis');

export default {
  name: 'SubProjectTable',
  components: {
    ParamDialog,
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    listKbli: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      loading: false,
      refresh: 0,
      typeOptions: [
        {
          value: 'utama',
          label: 'Kegiatan Utama',
        },
        {
          value: 'pendukung',
          label: 'Kegiatan Pendukung',
        },
      ],
      showParamDialog: false,
    };
  },
  methods: {
    async onChangeKbli(sproject){
      this.loading = true;
      await this.getBusinessByKbli(sproject);
      await this.getFieldByKbli(sproject);
      this.refresh++;
      this.loading = false;
    },
    async onChangeFieldType(value){
      this.loading = true;
      console.log(value);
      this.loading = false;
    },
    async getBusinessByKbli(sproject) {
      const { data } = await kbliEnvParamResource.list({
        kbli: sproject.kbli,
        businessType: true,
      });
      sproject.listSubProjectParams = data.map((i) => {
        return { param: i.param, scale_unit: i.unit };
      });
    },
    async getFieldByKbli(sproject) {
      const { data } = await kbliResource.list({
        fieldByKbli: sproject.kbli,
      });
      sproject.listField = data.map((i) => {
        return { value: i.id, label: i.value };
      });
    },
    handleClick(value){
      console.log(value);
      value.showParamDialog = true;
      this.refresh++;
    },
    handleCancelParam(value){
      console.log(value);
      value.showParamDialog = false;
      this.refresh++;
    },
    handleRefreshDialog(){
      this.refresh++;
    },
  },
};
</script>

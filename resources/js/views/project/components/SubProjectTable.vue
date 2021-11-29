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

      <el-table-column align="center" label="Non KBLI" width="100px">
        <template slot-scope="scope">
          <el-checkbox v-model="scope.row.nonKbli" @change="onChangeKbli(scope.row)" />
        </template>
      </el-table-column>

      <el-table-column align="center" label="KBLI" width="100px">
        <template slot-scope="scope">
          <div v-if="scope.row.nonKbli">{{ 'Non KBLI' }}</div>
          <el-select v-else v-model="scope.row.kbli" filterable placeholder="Pilih" size="mini" :disabled="scope.row.nonKbli" @change="onChangeKbli(scope.row)">
            <el-option
              v-for="item in listKbli"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Sektor">
        <template slot-scope="scope">
          <el-select v-model="scope.row.sector" filterable placeholder="Pilih" size="mini" @change="onChangeSector(scope.row)">
            <el-option
              v-for="item in scope.row.listSector"
              :key="item.label"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
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

      <el-table-column align="center" label="Nama Kegiatan">
        <template slot-scope="scope">
          <el-input
            v-model="scope.row.name"
            class="edit-input"
            size="mini"
          />
        </template>
      </el-table-column>

      <el-table-column align="center" label="Skala Besaran">
        <template slot-scope="scope">
          <el-button type="primary" @click="handleClick(scope.row)">Input</el-button>
          <param-dialog :show="scope.row.showParamDialog || false" :list="scope.row.listSubProjectParams" :refresh-dialog="refresh" :kbli="scope.row.biz_type ? scope.row.biz_type.toString() : scope.row.biz_type" @handleCancelParam="handleCancelParam(scope.row)" @handleRefreshDialog="handleRefreshDialog" />
        </template>
      </el-table-column>

      <el-table-column width="60px">
        <template slot-scope="scope">
          <el-popconfirm
            title="Hapus Kegiatan ?"
            @confirm="list.splice(scope.$index,1)"
          >
            <el-button slot="reference" type="danger" icon="el-icon-close" />
          </el-popconfirm>
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
      // await this.getBusinessByKbli(sproject);
      // reset value
      if (sproject.nonKbli){
        delete sproject.kbli;
      }
      delete sproject.sector;
      delete sproject.listSector;
      delete sproject.listField;
      delete sproject.biz_type;
      delete sproject.listSubProjectParams;

      await this.getSectorByKbli(sproject);
      this.refresh++;
      this.loading = false;
    },
    async onChangeSector(sproject){
      this.loading = true;
      await this.getFieldBySector(sproject);
      this.refresh++;
      this.loading = false;
    },
    async onChangeFieldType(sproject){
      this.loading = true;
      await this.getBusinessByField(sproject);
      console.log(sproject);
      this.loading = false;
    },
    async getBusinessByField(sproject) {
      const { data } = await kbliEnvParamResource.list({
        fieldId: sproject.biz_type,
        businessType: true,
      });
      sproject.listSubProjectParams = data.map((i) => {
        return { param: i.param, scale_unit: i.unit, id_param: i.id_param, id_unit: i.id_unit };
      });
    },
    async getFieldBySector(sproject) {
      const { data } = await kbliResource.list({
        fieldBySector: sproject.sector,
      });
      sproject.listField = data.map((i) => {
        return { value: i.id, label: i.value };
      });
    },
    async getSectorByKbli(sproject) {
      if (sproject.nonKbli) {
        const { data } = await kbliResource.list({
          sectorsByKbli: 'Non KBLI',
        });
        sproject.listSector = data.map((i) => {
          return { value: i.id, label: i.value };
        });
      } else if (sproject.kbli) {
        const { data } = await kbliResource.list({
          sectorsByKbli: sproject.kbli,
        });
        sproject.listSector = data.map((i) => {
          return { value: i.id, label: i.value };
        });
      }
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

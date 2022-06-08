<template>
  <div>
    <el-table
      :key="refresh"
      v-loading="loading"
      max-height="800"
      :header-cell-style="{ background: '#099C4B', color: 'white' }"
      :data="list"
      fit
      highlight-current-row
    >
      <el-table-column align="center" width="55">
        <template slot-scope="scope">
          <el-checkbox v-model="scope.row.isUsed" @change="onChangeIsUsed(scope.row)" />
        </template>
      </el-table-column>

      <el-table-column align="center" label="No." width="50px">
        <template slot-scope="scope">
          {{ scope.$index + 1 }}
        </template>
      </el-table-column>

      <el-table-column v-if="fromOss" align="left" header-align="center" label="Project" width="400px">
        <template slot-scope="scope">
          <div><b>ID Proyek :</b> {{ scope.row.id_proyek }}</div>
          <div><b>ID Izin :</b> {{ scope.row.idizin }}</div>
          <div><b>Nama Kegiatan :</b> {{ scope.row.name }}</div>
          <div><b>KBLI :</b> {{ scope.row.kbli }}</div>
          <div><b>Tingkat Resiko :</b> {{ scope.row.skala_resiko }}</div>
          <div><b>Kewenangan :</b> {{ getKewenangan(scope.row.kewenangan) }}</div>
          <div><b>Alamat :</b></div>
          <ul style="margin-block-start: 0px">
            <li v-for="(lokasi, index) in scope.row.lokasi" :key="index">Provinsi {{ lokasi.province.toLowerCase() }}, {{ lokasi.regency.toLowerCase() }}, {{ lokasi.alamat_usaha.toLowerCase() }}</li>
          </ul>
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

      <el-table-column v-if="!pemerintah" align="center" label="Non KBLI" width="100px">
        <template slot-scope="scope">
          <el-checkbox v-model="scope.row.nonKbli" :disabled="fromOss" @change="onChangeKbli(scope.row)" />
        </template>
      </el-table-column>

      <el-table-column v-if="!pemerintah" align="center" label="KBLI" width="100px">
        <template slot-scope="scope">
          <div v-if="scope.row.nonKbli">{{ 'Non KBLI' }}</div>
          <el-select v-else v-model="scope.row.kbli" filterable placeholder="Pilih" size="mini" :disabled="scope.row.nonKbli || fromOss" @change="onChangeKbli(scope.row)">
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
          <el-select v-if="!pemerintah" v-model="scope.row.sector" filterable placeholder="Pilih" size="mini" @change="onChangeSector(scope.row)" @focus="onChangeSectorBlur(scope.row)">
            <el-option
              v-for="item in scope.row.listSector"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
          <el-select v-else v-model="scope.row.sector" filterable placeholder="Pilih" size="mini" @change="onChangeSector(scope.row)">
            <el-option
              v-for="item in sectors"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Jenis Rencana Kegiatan">
        <template slot-scope="scope">
          <el-tooltip :key="refresh" class="item" effect="dark" :content="scope.row.biz_name || 'Pilih'" placement="bottom">
            <el-select v-model="scope.row.biz_type" filterable placeholder="Pilih" size="mini" @change="onChangeFieldType(scope.row)">
              <el-option
                v-for="item in scope.row.listField"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-tooltip>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Nama Kegiatan">
        <template slot-scope="scope">
          <el-input
            v-model="scope.row.name"
            class="edit-input"
            size="mini"
            maxlength="200"
          />
        </template>
      </el-table-column>

      <el-table-column align="center" label="Skala Besaran">
        <template slot-scope="scope">
          <el-button type="primary" @click="handleClick(scope.row)">Input</el-button>
          <param-dialog :show="scope.row.showParamDialog || false" :list="scope.row.listSubProjectParams" :refresh-dialog="refresh" :kbli="scope.row.biz_type ? scope.row.biz_type.toString() : scope.row.biz_type" :sector="scope.row.sector ? scope.row.sector.toString() : scope.row.sector" @handleCancelParam="handleCancelParam(scope.row)" @handleRefreshDialog="handleRefreshDialog" />
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
const kbliResource = new Resource('business');

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
    fromOss: {
      type: Boolean,
      default: () => false,
    },
    idizin: {
      type: String,
      default: () => '',
    },
    pemerintah: {
      type: Boolean,
      default: () => false,
    },
    sectors: {
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
    getKewenangan(val){
      if (val === '00'){
        return 'Pusat';
      } else if (val === '01'){
        return 'Provinsi';
      } else {
        return 'Kabupaten';
      }
    },
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
    async onChangeSectorBlur(sproject){
      if (sproject.listSector){
        return;
      }

      this.loading = true;
      try {
        await this.getSectorByKbli(sproject);
      } catch (error) {
        console.error(error);
        this.refresh++;
        this.loading = false;
      }

      this.refresh++;
      this.loading = false;
    },
    async onChangeSector(sproject){
      this.loading = true;
      await this.getFieldBySector(sproject);
      this.refresh++;
      this.loading = false;
    },
    async onChangeIsUsed(sproject){
      this.$emit('handlechecked', sproject);
    },
    async onChangeFieldType(sproject){
      this.loading = true;
      // const { value } = await kbliResource.get(sproject.biz_type);
      // sproject.biz_name = value;
      // console.log(sproject.biz_name);
      await this.getBusinessByField(sproject);
      console.log(sproject);
      this.loading = false;
      this.refresh++;
    },
    async getBusinessByField(sproject) {
      // console.log(sproject.biz_name);

      if (this.pemerintah){
        // const { value } = await kbliResource.get(sproject.sector);

        sproject.biz_name = sproject.biz_type;

        const { data } = await kbliEnvParamResource.list({
          businessTypePem: sproject.biz_name,
          sectorName: sproject.sector,
        });

        sproject.listSubProjectParams = data.map((i) => {
          return { param: i.param, scale_unit: i.unit, id_param: i.id_param, id_unit: i.id_unit };
        });
      } else {
        const { value } = await kbliResource.get(sproject.biz_type);
        sproject.biz_name = value;
        const { data } = await kbliEnvParamResource.list({
          fieldId: sproject.biz_type,
          businessType: true,
        });
        sproject.listSubProjectParams = data.map((i) => {
          return { param: i.param, scale_unit: i.unit, id_param: i.id_param, id_unit: i.id_unit };
        });
      }
    },
    async getFieldBySector(sproject) {
      if (this.pemerintah){
        // const { value } = await kbliResource.get(sproject.sector);

        const { data } = await kbliResource.list({
          fieldBySectorName: sproject.sector,
        });

        sproject.sector_name = sproject.sector;

        sproject.listField = data.map((i) => {
          return { value: i.value, label: i.value };
        });
      } else {
        const { data } = await kbliResource.list({
          fieldBySector: sproject.sector,
        });
        sproject.listField = data.map((i) => {
          return { value: i.id, label: i.value };
        });
      }
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
      this.$emit('cancel');
      value.showParamDialog = false;
      this.refresh++;
    },
    handleRefreshDialog(){
      this.refresh++;
    },
    handleSelectionChange(value){
      this.$emit('checksubpro', value);
    },
  },
};
</script>
<style>
.el-select-dropdown{
  width: 250px;
  word-wrap: break-word;
}
</style>

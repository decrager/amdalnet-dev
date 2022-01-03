<template>
  <el-table
    v-loading="loading"
    :data="data"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#def5cf' }"
    style="width: 100%"
  >
    <el-table-column type="expand" class="row-detail">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage" class="post">
          <div class="entity-block">
            <div>Wilayah Studi</div>
            <el-input v-model="scope.row.study_location" :readonly="isAndal" />
            <div>Batas Waktu Kajian</div>
            <el-input-number v-model="scope.row.study_length_year" controls-position="right" :min="0" :max="100" :disabled="isAndal" />
            tahun
            <el-input-number v-model="scope.row.study_length_month" controls-position="right" :min="0" :max="11" :disabled="isAndal" />
            bulan
          </div>
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Rencana Kegiatan yang Berpotensi">
      <template slot-scope="scope">
        <div v-if="scope.row.is_stage">
          <b>{{ scope.row.index }}. {{ scope.row.project_stage_name }}</b>
        </div>
        <div v-if="!scope.row.is_stage">
          {{ scope.row.index }}. {{ scope.row.rona_awal_name }}
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Komponen Lingkungan Terkena Dampak" align="center">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          {{ scope.row.component_name }}
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Dampak Potensial" align="center">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          {{ scope.row.change_type_name }} {{ scope.row.rona_awal_name }} akibat {{ scope.row.component_name }}
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Evaluasi Dampak Potensial" align="center">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          <el-input v-model="scope.row.potential_impact_evaluation" type="textarea" :rows="2" />
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Dampak Penting Hipotetik" align="center">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          <el-select
            v-model="scope.row.is_hypothetical_significant"
            placeholder="Tidak Menjadi DPH"
            style="width: 100%"
            :disabled="isAndal"
          >
            <el-option
              v-for="item of dphOptions"
              :key="item.value"
              :label="item.name"
              :value="item.value"
            />
          </el-select>
        </div>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
// import Resource from '@/api/resource';
// const unitResource = new Resource('units');

export default {
  name: 'DampakPentingHipotetikTable',
  props: {
    data: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      loading: true,
      dphOptions: [],
      unitOptions: [],
    };
  },
  computed: {
    isAndal() {
      return this.$route.name === 'penyusunanAndal';
    },
  },
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      this.dphOptions = [
        {
          name: 'Menjadi DPH',
          value: true,
        },
        {
          name: 'Tidak Menjadi DPH',
          value: false,
        },
      ];
      this.loading = false;
    },
  },
};
</script>

<style lang="scss" scoped>
.entity-block {
  display: inline-block;

  .name, .description {
    display: block;
    margin-left: 50px;
    padding: 2px 0;
  }
  .action {
    display:  inline-block;
    padding-right: 5%;
  }
  img {
    width: 40px;
    height: 40px;
    float: left;
  }
  :after {
    clear: both;
  }
  .img-circle {
    border-radius: 50%;
    border: 2px solid #d2d6de;
    padding: 2px;
  }
  span {
    font-weight: 500;
    font-size: 12px;
  }

}
.post {
  font-size: 14px;
  margin-bottom: 15px;
  padding-right: 20px;
  padding-left: 7%;
  color: #666;
  .image {
    width: 100%;
  }
  .user-images {
    padding-top: 20px;
  }
  .title {
    display: block;
    padding: 2px 0;
  }
}
.list-inline {
  padding-left: 0;
  margin-left: -5px;
  list-style: none;
  li {
    display: inline-block;
    padding-right: 5px;
    padding-left: 5px;
    font-size: 13px;
  }
  .link-black {
    &:hover, &:focus {
      color: #999;
    }
  }
}
</style>

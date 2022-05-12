<template>
  <div>
    <div style="margin-bottom: 1em;">
      <el-radio-group v-model="query.filters" @change="onChangeRequiredDoc">
        <el-radio-button label="AMDAL" />
        <el-radio-button label="UKL-UPL" />
      </el-radio-group>
    </div>
    <el-table
      v-loading="loading"
      :data="tableData"
      stripe
      style="width: 100%; background-color: #041608 !important;"
    >
      <el-table-column
        type="index"
        :index="indexMethod"
        width="50"
      />
      <el-table-column
        prop="applicant"
        label="Pemrakarsa"
        width="250"
      />
      <el-table-column
        prop="project_title"
        label="Nama Kegiatan"
      />
      <el-table-column
        prop="authority"
        label="Kewenangan"
        align="center"
        width="180"
      />
      <el-table-column
        label="Dokumen Lingkungan"
        align="center"
        width="400"
      >
        <template slot-scope="scope">
          <el-button type="primary" style="color: white !important" @click="showProject(scope.row.id)">AMDAL Digital</el-button>
          <!-- <el-button type="warning" style="color: white !important" @click="showDocument(scope.row.id)">Dokumen</el-button> -->
        </template>
      </el-table-column>
    </el-table>

    <div class="block" style="text-align: right">
      <pagination
        v-if="query.total > 0"
        :auto-scroll="false"
        :total="query.total"
        :page.sync="query.page"
        :limit.sync="query.limit"
        @pagination="getData"
      />
    </div>
  </div>
</template>
<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';

const projectResource = new Resource('projects');

export default {
  name: 'ListProyek',
  components: {
    Pagination,
  },
  data(){
    return {
      tableData: [],
      data: [],
      loading: false,
      query: {
        total: 0,
        page: 1,
        limit: 10,
        orderBy: 'id',
        order: 'DESC',
        filters: 'AMDAL',
      },
      requiredDoc: 'AMDAL',
    };
  },
  mounted(){
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      this.tableData = [];
      await projectResource.list(this.query)
        .then((res) => {
          // console.log('Data Rumah Kegiatan:', res);
          this.tableData = res.data;
          this.query.total = res.total;
          this.query.page = res.current_page;
          this.query.limit = res.per_page;
          // this.initiator = res.initiator;
          // this.announcement_id = res.announcement_id;
          // console.log('rumah kegiatan annoucement:', this.announcement_id);
          // this.processData();
        })
        .finally(() => {
          this.loading = false;
        });
      // console.log('Rumah Kegiatan ID: ', this.project_id);
    },
    onChangeRequiredDoc(val){
      this.query.total = 0;
      this.query.page = 1;
      this.getData();
    },
    indexMethod(index) {
      return ((this.query.page - 1) * this.query.limit) + index + 1;
    },
    showProject(id) {
      const project = this.tableData.find(p => p.id === id);
      if (project){
        this.$emit('showProject', id);
      }
    },
    showDocument(id){
      this.$emit('showDocument', id);
    },
  },
};
</script>

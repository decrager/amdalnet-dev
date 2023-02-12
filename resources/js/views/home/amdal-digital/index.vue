<template>
  <section id="amdal-digital" class="amdal-digital section">
    <div class="container">
      <h2 class="section__title announce__title amdal-digital__title">AMDAL Digital</h2>
      <div v-if="!showProjectHome" class="list-of-projects">
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
              <el-button v-if="scope.row.required_doc === 'UKL-UPL'" type="warning" style="color: white !important" @click="showDocument(scope.row.id, 'UKL-UPL')">Dokumen UKL - UPL</el-button>
              <el-button v-if="scope.row.required_doc === 'AMDAL'" type="warning" style="color: white !important" @click="showDocument(scope.row.id, 'Dokumen KA')">Dokumen KA</el-button>
              <el-button v-if="scope.row.required_doc === 'AMDAL'" type="warning" style="color: white !important; margin-top: .5rem;" @click="showDocument(scope.row.id, 'Dokumen ANDAL')">Dokumen ANDAL</el-button>
              <el-button v-if="scope.row.required_doc === 'AMDAL'" type="warning" style="color: white !important; margin-top: .5rem;" @click="showDocument(scope.row.id, 'Dokumen RKL RPL')">Dokumen RKL - RPL</el-button>
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
        <el-dialog :visible.sync="dialogVisible">
          <iframe
            sandbox="allow-scripts allow-same-origin"
            :src="`https://docs.google.com/gview?url=${encodeURIComponent(
              urlPdf
            )}&embedded=true`"
            width="100%"
            loading="lazy"
            height="723px"
            frameborder="0"
          />
        </el-dialog>
      </div>
      <div v-show="showProjectHome" class="public-project-home">
        <div style="text-align: right; margin-bottom:1em;">
          <el-button icon="el-icon-close" type="danger" circle @click="showProjectHome = false" />
        </div>
        <project-public-home :project-id="id" />
      </div>
    </div>
  </section>
</template>
<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import ProjectPublicHome from '@/views/project/Home/public.vue';
import axios from 'axios';

const skklResource = new Resource('skkl');
const projectResource = new Resource('projects');

export default {
  name: 'AmdalDigital',
  components: {
    Pagination,
    ProjectPublicHome,
  },
  data(){
    return {
      tableData: [],
      dialogVisible: false,
      data: [],
      urlPdf: null,
      loading: false,
      id: 0,
      showProjectHome: false,
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
        })
        .finally(() => {
          this.loading = false;
        });
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
      this.id = id;
      this.showProjectHome = true;
    },
    async getDoc(id, required_doc) {
      if (required_doc === 'UKL-UPL') {
        const data = await axios.get(
          `/api/dokumen-ukl-upl/${id}`
        );
        this.urlPdf = data.data.pdf_url;
        this.dialogVisible = true;
        this.loading = false;
      }

      if (required_doc === 'Dokumen KA' || required_doc === 'Dokumen ANDAL' || required_doc === 'Dokumen RKL RPL') {
        const data = await skklResource.list({
          idProject: id,
          document: 'true',
          amdalDigital: 'true',
        });
        data.forEach(ref => {
          if (ref.name === 'Dokumen KA' && required_doc === 'Dokumen KA') {
            this.urlPdf = ref.file;
            this.dialogVisible = true;
          } else if (ref.name === 'Dokumen ANDAL' && required_doc === 'Dokumen ANDAL') {
            this.urlPdf = ref.file;
            this.dialogVisible = true;
          } else if (ref.name === 'Dokumen RKL RPL' && required_doc === 'Dokumen RKL RPL') {
            this.urlPdf = ref.file;
            this.dialogVisible = true;
          }
        });
        this.loading = false;
      }
    },
    showDocument(id, required_doc){
      this.urlPdf = '';
      this.loading = true;
      this.getDoc(id, required_doc);
      this.$emit('showDocument', id);
    },
  },
};
</script>
<style>
#amdal-digital .el-radio-button .el-radio-button__inner {
  background-color: #041608 ;
  border-color: #041608 ;
  font-weight: bold;
  color: #fff;
}

#amdal-digital .el-radio-button__orig-radio:checked + .el-radio-button__inner {
  background-color: #f6993f ;
  border-color: #f6993f;
}

#amdal-digital .el-table th.el-table__cell {
  background-color: #041608 !important;
}
#amdal-digital .el-table td.el-table__cell{
  background-color: #041608 !important;
  border-color: #333 !important;
  color: #fff;
  padding: 0.5em;
}

</style>

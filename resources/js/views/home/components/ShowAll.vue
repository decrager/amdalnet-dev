<template>
  <div style="margin-top:2rem;">
    <div v-if="showFromAll" style="margin-top:2rem;">
      <el-form ref="form">
        <el-row :gutter="20">
          <el-col :xs="24" :sm="5">
            <div style="display:flex; align-items:center;">
              <h5 style="margin-left:1.5rem; margin-right:1rem;">Urutkan</h5>
              <el-form-item label="">
                <el-select v-model="form.sort" style="margin-left:auto" placeholder="Terbaru" @change="handleChange">
                  <el-option label="Descending" value="DESC" />
                  <el-option label="Ascending" value="ASC" />
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :xs="24" :sm="19">
            <div style="display:flex; align-items:center; justify-content: end;">
              <el-form-item ref="form" label="">
                <el-input v-model="keyword" type="text" placeholder="Global search (All field)" />
              </el-form-item>
              <el-button type="warning fw-bold" plain @click="handleSearch">Cari</el-button>
              <el-button type="info fw-bold" @click="handleCancle">
                <i class="el-icon-d-arrow-left" /> Kembali
              </el-button>
            </div>
          </el-col>
        </el-row>
      </el-form>
      <el-row v-for="amdal in allData" :key="amdal.id" :gutter="20" class="wrapOutside">
        <el-col :xs="24" :sm="3" style="padding-top:1rem">
          <img alt="" src="/images/list.svg">
        </el-col>
        <el-col :xs="24" :sm="18" style="padding-top:1rem">
          <h3 class="tw">{{ amdal.project_type }}, {{ amdal.project && amdal.project.province ? amdal.project.province.name : '' }}</h3>
          <h3 class="fw-bold to mt-2-5">{{ amdal.pic_name }}</h3>
          <p class="tw">Pengumuman : {{ formatDateStr(amdal.start_date) }} | {{ amdal.feedbacks_count }} Tanggapan</p>
        </el-col>
        <el-col :xs="24" :sm="3">
          <button type="button" class="el-button el-button--success el-button--medium is-plain"><span>{{ amdal.project_result }}</span></button>
          <button class="buttonCustom to" @click="openDetails(amdal.id)">Lihat Detail</button>
        </el-col>
      </el-row>
      <div class="block" style="text-align:right">
        <pagination
          v-show="total > 0"
          :total="total"
          :page.sync="listQuery.page"
          :limit.sync="listQuery.limit"
          @pagination="handleFilter"
        />
      </div>
    </div>
    <div v-if="showDetailFromAll">
      <Details
        :selected-announcement="selectedAnnouncement"
        :selected-project="selectedProject"
        @handleCancelComponent="handleCancelComponent"
      />
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import Pagination from '@/components/Pagination';
import Details from './Details';

export default {
  name: 'ShowAll',
  components: {
    Pagination,
    Details,
  },
  data() {
    return {
      form: {
        name: '',
        sort: '',
        delivery: false,
        type: [],
        resource: '',
        desc: '',
      },
      allData: [],
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
      },
      keyword: '',
      showDetailFromAll: false,
      showFromAll: true,
      selectedAnnouncement: {},
      selectedProject: {},
    };
  },
  created() {
    this.getAll();
  },
  methods: {
    handleFilter() {
      this.getAll();
    },
    formatDateStr(date) {
      const today = new Date(date);
      var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
      const year = today.getFullYear();
      const month = today.getMonth();
      const day = today.getDate();
      const monthID = bulan[month];
      const finalDate = `${day} ${monthID} ${year}`;
      return finalDate;
    },
    getAll(search, sort){
      if (search === 'undefined'){
        search = '';
      } else {
        search = `&keyword=${this.keyword}`;
      }
      if (sort === 'undefined'){
        sort = `&sort=DESC`;
      } else {
        sort = `&sort=${this.form.sort}`;
      }
      axios.get(`/api/announcements?page=${this.listQuery.page}${search}${sort}`)
        .then(response => {
          this.allData = response.data.data;
          this.total = response.data.total;
        });
    },
    openDetails(id) {
      axios.get('/api/announcements/' + id)
        .then(response => {
          this.selectedAnnouncement = response.data;
          this.selectedProject = response.data.project;
          this.showDetailFromAll = true;
          this.showFromAll = false;
        });
    },
    handleCancle(){
      this.$emit('handleCancle');
    },
    handleSearch(){
      this.getAll(this.keyword);
    },
    handleChange(){
      this.getAll(this.form.sort);
    },
    handleCancelComponent(){
      this.showDetailFromAll = false;
      this.showFromAll = true;
    },
  },
};
</script>
<style>
  .wrapOutside{padding:0 0.5rem; background:#062307; border-radius: 5px; align-items:end; border-bottom:1px solid #fff;}
  .wrapOutside.el-row:hover{background: #184d1a;}
  .wrapImage{width:100%; height:150px}
  .imgCompany{width:100%; height:100%; object-fit: contain; object-position: center;}
  .tw{color:#fff}
  .to{color:#f6993f}
  .fw-bold{font-weight:bold}
  .buttonCustom{background: transparent;font-weight: bold;}
  .mt-05{margin-top:1.5rem}
  .mt-2-5{margin-top:2.5rem}
  .wrapButton{display:flex; margin:6rem 0 2rem 0}
  .el-form-item.el-form-item--medium{margin-bottom:10px}
  .el-button.el-button--warning.el-button--medium.is-plain{color: #fff;background: #FFBA00;border-color: #FFBA00;margin-bottom:10px;margin-left: 1rem;}
  .wrapSelect{width: 60%;margin-left: auto;}
  .el-pager li{background:transparent; color:#eb933c;}
  .el-pagination .btn-prev, .el-pagination .btn-next{color: #f6993f;background-color: transparent;}
  .el-pagination button:disabled {background-color: transparent;color: #f6993f;}
  .el-button--success.is-plain {color: #fff;background: #13ce66;border-color: #13ce66;border-radius: 2rem; margin-bottom:0.5rem; margin-top:3.5rem}
  .pagination-container[data-v-5efa73f0] {background: transparent;padding: 0;}
  .el-pagination span:not([class*="suffix"]), .el-pagination button {color: #fff;}
  .el-button.el-button--info.fw-bold.el-button--medium {margin-bottom: 10px;}
</style>

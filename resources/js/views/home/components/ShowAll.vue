<template>
  <div v-if="showAllTabs" style="margin-top:2rem;">
    <el-form ref="form">
      <el-row :gutter="20">
        <el-col :xs="24" :sm="5">
          <div style="display:flex; align-items:center;">
            <h5 style="margin-left:1.5rem; margin-right:1rem;">Urutkan</h5>
            <el-form-item label="">
              <el-select v-model="form.region" style="width:60%; margin-left:auto" placeholder="Terbaru">
                <el-option label="Zone one" value="shanghai" />
                <el-option label="Zone two" value="beijing" />
              </el-select>
            </el-form-item>
          </div>
        </el-col>
        <el-col :xs="24" :sm="19">
          <div style="display:flex; align-items:center; justify-content: end;">
            <el-form-item ref="form" label="">
              <el-input type="text" placeholder="Global search (All field)" />
            </el-form-item>
            <el-button type="warning fw-bold" plain>Cari</el-button>
            <el-button type="warning fw-bold" plain @click="handleCancle">Kembali</el-button>
          </div>
        </el-col>
      </el-row>
    </el-form>
    <el-row :gutter="20" class="wrapOutside">
      <el-col :xs="24" :sm="3" style="padding-top:1rem">
        <!-- <div class="wrapImage"> -->
        <img class="imgCompany" src="https://placeimg.com/100/100/tech">
        <!-- </div> -->
      </el-col>
      <el-col :xs="24" :sm="18" style="padding-top:1rem">
        <h3 class="tw">Pembangunan Pabrik Pengolahan Susu Kemasan (2022) Kota Sukabumi, Prov Jawa Barat</h3>
        <h3 class="fw-bold to mt-2-5">PT Pertamina</h3>
        <p class="tw">Pengumuman : 28 Septembet 2021 | 2 Tanggapan</p>
      </el-col>
      <el-col :xs="24" :sm="3">
        <div class="wrapButton">
          <button class="buttonCustom to">Lihat Detail</button>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="wrapOutside">
      <el-col :xs="24" :sm="3" style="padding-top:1rem">
        <!-- <div class="wrapImage"> -->
        <img class="imgCompany" src="https://placeimg.com/100/100/tech">
        <!-- </div> -->
      </el-col>
      <el-col :xs="24" :sm="18" style="padding-top:1rem">
        <h3 class="tw">Pembangunan Pabrik Pengolahan Susu Kemasan (2022) Kota Sukabumi, Prov Jawa Barat</h3>
        <h3 class="fw-bold to mt-2-5">PT Pertamina</h3>
        <p class="tw">Pengumuman : 28 Septembet 2021 | 2 Tanggapan</p>
      </el-col>
      <el-col :xs="24" :sm="3">
        <div class="wrapButton">
          <button class="buttonCustom to">Lihat Detail</button>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="wrapOutside">
      <el-col :xs="24" :sm="3" style="padding-top:1rem">
        <!-- <div class="wrapImage"> -->
        <img class="imgCompany" src="https://placeimg.com/100/100/tech">
        <!-- </div> -->
      </el-col>
      <el-col :xs="24" :sm="18" style="padding-top:1rem">
        <h3 class="tw">Pembangunan Pabrik Pengolahan Susu Kemasan (2022) Kota Sukabumi, Prov Jawa Barat</h3>
        <h3 class="fw-bold to mt-2-5">PT Pertamina</h3>
        <p class="tw">Pengumuman : 28 Septembet 2021 | 2 Tanggapan</p>
      </el-col>
      <el-col :xs="24" :sm="3">
        <div class="wrapButton">
          <button class="buttonCustom to">Lihat Detail</button>
        </div>
      </el-col>
    </el-row>
    <div class="block" style="text-align:right">
      <el-pagination
        layout="prev, pager, next"
        :total="50"
      />
    </div>
  </div>
</template>
<script>
export default {
  name: 'ShowAll',
  props: {
    showAllTabs: Boolean,
  },
  data() {
    return {
      form: {
        name: '',
        region: '',
        delivery: false,
        type: [],
        resource: '',
        desc: '',
      },
    };
  },
  computed: {},
  created() {},
  methods: {
    handleCancle(){
      this.$emit('handleCancle');
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
</style>

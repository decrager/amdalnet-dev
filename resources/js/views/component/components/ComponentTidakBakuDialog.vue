<template>
  <el-dialog :title="'Pembakuan Komponen'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <div style="padding: 0 2em;">
      <p style="line-height: 1.5em;"><span style="font-weight: bold;">Tahap</span><br> <span style="font-size: 1.25em;">{{ component.project_stage }}</span> </p>
      <p style="line-height: 1.5em;"><span style="font-weight: bold;">Komponen</span><br> <span style="font-size: 1.25em;">{{ component.name }}</span> </p>
      <p style="line-height: 1.5em;"><span style="font-weight: bold;">Asal Kegiatan</span><br> <span style="font-size: 1.25em;">{{ component.project_title }}</span> </p>

      <!-- <el-alert
        v-if="isSafe === false"
        :title="'Sudah ada komponen baku '+ component.name + ' pada tahap '+ component.project_stage "
        type="error"
        :closable="false">
      </el-alert> -->

    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancelComponent()"> Tutup </el-button>
      <!-- <el-button v-if="isSafe" type="primary" @click="handleSubmitComponent()"> Bakukan </el-button> -->
    </div>

  </el-dialog>
</template>
<script>
import Resource from '@/api/resource';
const checkMasterComponentResource = new Resource('master-component');

export default {
  name: 'ComponentTidakBakuDialog',
  props: {
    show: { type: Boolean, default: false },
    component: { type: Object, default: () => {} },
  },
  data(){
    return {
      isSafe: false,
      loading: false,
    };
  },
  methods: {
    handleCancelComponent(){
      this.$emit('close', true);
    },
    async checkMasterComponent(){
      this.loading = true;
      await checkMasterComponentResource.list({ stage: this.component.id_project_stage, name: this.component.name })
        .then(res => {
          this.isSafe = res.data === null;
        }).finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>

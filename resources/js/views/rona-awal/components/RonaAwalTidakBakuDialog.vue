<template>
  <el-dialog :title="'Pembakuan Komponen'" :visible.sync="show" :close-on-click-modal="false" :show-close="false" @opened="onOpened" @close="onClose">
    <div style="padding: 0 2em;">
      <p style="line-height: 1.5em;"><span style="font-weight: bold;">Jenis Komponen</span><br> <span style="font-size: 1.25em;">{{ component.component }}</span> </p>
      <p style="line-height: 1.5em;"><span style="font-weight: bold;">Rona Awal</span><br> <span style="font-size: 1.25em;">{{ component.name }}</span> </p>
      <p style="line-height: 1.5em;"><span style="font-weight: bold;">Asal Kegiatan</span><br> <span style="font-size: 1.25em;">{{ component.project_title }}</span> </p>

      <div v-if="queried">
        <div v-if="!component.is_master">
          <el-button v-if="isSafe" type="primary" @click="handleSubmitComponent()"> Bakukan </el-button>
          <div v-else style="">
            <p>Sudah ada master data rona awal baku <strong>{{ component.name }}</strong> dengan jenis komponen <strong>{{ component.component }}</strong>.</p>
          </div>
        </div>
        <div v-else>
          <p>Rona Lingkungan <strong>{{ component.name }}</strong> dengan jenis komponen <strong>{{ component.component }}</strong> berhasil dibakukan.</p>
        </div>
      </div>
    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancelComponent()"> Tutup </el-button>
    </div>

  </el-dialog>
</template>
<script>
import Resource from '@/api/resource';
const checkMasterComponentResource = new Resource('master-ronaawal');

export default {
  name: 'RonaAwalTidakBakuDialog',
  props: {
    show: { type: Boolean, default: false },
    component: { type: Object, default: () => {} },
  },
  data(){
    return {
      isSafe: false,
      loading: false,
      queried: false,
      formalised: false,
    };
  },
  methods: {
    handleCancelComponent(){
      this.$emit('close', this.formalised);
    },
    async checkMasterComponent(){
      this.loading = true;
      await checkMasterComponentResource.list({ component: this.component.id_component_type, name: this.component.name })
        .then(res => {
          this.isSafe = res.data === null;
        }).finally(() => {
          this.loading = false;
          this.queried = true;
        });
    },
    onOpened(){
      console.log('onOpened...', this.component.name);
      this.formalised = false;
      this.checkMasterComponent();
    },
    onClose(){
      this.isSafe = false;
      this.queried = false;
    },
    async handleSubmitComponent(){
      await checkMasterComponentResource.store({ id: this.component.id })
        .then(res => {
          this.component.is_master = res.data.is_master;
          if (this.component.is_master) {
            this.formalised = true;
            this.$message({
              type: 'success',
              message: res.message,
              duration: 5 * 1000,
            });
          }
        })
        .catch((err) => {
          console.log(err);
        })
        .finally(() => {

        });
    },
  },
};
</script>

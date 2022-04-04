<template>
  <div class="delete-component-wrapper">
    <el-dialog
      :title="'Hapus Komponen '+ label"
      :visible.sync="show"
      width="40%"
      height="350"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
      @open="onOpen"
    >
      <div v-loading="loading" style="padding: 1em;">
        <div v-if="hasChildren && (children.length > 0)">
          <div style="word-break:break-word; margin-bottom: 1em;">
            Komponen {{ label }} <strong>{{ component.name }}</strong> sudah memiliki rekaman dampak potensial.
            Menghapus Komponen {{ label }} <strong>{{ component.name }}</strong> akan turut menghapus dampak potensial tersebut.
          </div>
          <p style="word-break:break-word;">Lanjutkan hapus Komponen {{ label }} <strong>{{ component.name }}</strong> dan semua dampak terkait?</p>

        </div>
        <div v-else>
          <p style="word-break:break-word;">Hapus komponen {{ label }} <strong>{{ component.name }}</strong> ?</p>
        </div>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button :disabled="loading" @click="handleClose">Batal</el-button>
        <el-button type="danger" :disabled="loading" @click="deleteComponent">Hapus Komponen</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>

import Resource from '@/api/resource';
export default {
  name: 'FormDeleteComponent',
  props: {
    component: {
      type: Object,
      default: null,
    },
    id: {
      type: Number,
      default: 0,
    },
    show: {
      type: Boolean,
      default: false,
    },
    resource: {
      type: String,
      default: '',
    },
  },
  data(){
    return {
      id_project: 0,
      hasChildren: false,
      children: [],
      loading: false,
      label: '',
    };
  },
  created(){
    // this.project_id = parseInt(this.$route.params && this.$route.params.id);
    this.onOpen();
  },
  methods: {
    onOpen(){
      this.getRelatedData();
      this.assignLabel();
    },
    handleClose(){
      this.$emit('close', true);
    },
    assignLabel(){
      switch (this.resource){
        case 'project-components':
          this.label = 'Kegiatan';
          break;
        case 'project-rona-awals':
          this.label = 'Lingkungan';
          break;
        case 'sub-project-components':
          this.label = 'Kegiatan';
          break;
        case 'sub-project-rona-awals':
          this.label = 'Rona Awal';
          break;
        default:
      }
    },
    getIdComponent(){
      let id = 0;
      switch (this.resource){
        case 'project-components':
          id = this.component.id_project_component;
          break;
        case 'project-rona-awals':
          id = this.component.id_project_rona_awal;
          break;
        case 'sub-project-components':
          id = this.component.id_sub_project_component;
          break;
        case 'sub-project-rona-awals':
          id = this.component.id_sub_project_rona_awal;
          break;
        default:
      }
      return id;
    },
    async getRelatedData(){
      this.loading = true;
      const id_project = parseInt(this.$route.params && this.$route.params.id);
      this.hasChildren = true;
      this.children = [];
      const resource = new Resource(this.resource);
      this.component.inquire = 1;
      this.component.id_project = id_project;
      await resource.list(this.component)
        .then((res) => {
          if (res.length > 0) {
            this.hasChildren = true;
            this.children = res;
          }
        }).finally(() => {
          this.loading = false;
        });
    },
    async deleteComponent(){
      this.loading = true;
      const resource = new Resource(this.resource);
      await resource.destroy(this.getIdComponent())
        .then((res) => {
          console.log(res);
          this.$message({
            message: 'Komponen berhasil dihapus',
            type: 'success',
            duration: 5 * 1000,
          });
          this.$emit('delete', this.component.id);
        }).catch((err) => {
          this.$message({
            message: 'Komponen gagal dihapus',
            type: 'error',
            duration: 5 * 1000,
          });
          console.log(err);
        }).finally(() => {
          this.loading = false;
          this.handleClose();
        });
    },
  },

};
</script>

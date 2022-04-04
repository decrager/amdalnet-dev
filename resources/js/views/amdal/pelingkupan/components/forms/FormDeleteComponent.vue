<template>
  <div>
    <el-dialog
      :title="'Hapus Master Data Komponen Kegiatan'"
      :visible.sync="show"
      width="40%"
      height="350"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
      @open="onOpen"
    >
      <div v-loading="loading">
        <div v-if="hasChildren && (children.length > 0)">
          <p>Komponen Kegiatan {{ component.name }} sudah digunakan dalam Identifikasi Dampak.</p>
          <p> Hapus semua dampak terkait Komponen Kegiatan <strong>{{ component.name }}</strong>?</p>
        </div>
        <div v-else>
          <p>Hapus komponen Kegiatan <strong>{{ component.name }}</strong> ?</p>
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
// import Deskripsi from '../helpers/Deskripsi.vue';
// const componentResource = new Resource('components');
const projectComponentResource = new Resource('project-components');

export default {
  name: 'FormDeleteComponent',
  // components: { Deskripsi },
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
  },
  data(){
    return {
      id_project: 0,
      hasChildren: false,
      children: [],
      loading: false,
    };
  },
  created(){
    // this.project_id = parseInt(this.$route.params && this.$route.params.id);
    this.onOpen();
  },
  methods: {
    onOpen(){
      this.getRelatedData();
    },
    handleClose(){
      this.$emit('close', true);
    },
    async getRelatedData(){
      this.loading = true;
      const id_project = parseInt(this.$route.params && this.$route.params.id);
      await projectComponentResource.list({
        id_project: id_project,
        id: this.component.id,
        inquire: 1,
      }).then((res) => {
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
      await projectComponentResource.destroy(this.component.id_project_component
        /* {
        id: this.component.id_project_component,
        id_project: this.id_project,
        id_component: this.component.id,
        description: this.component.description,
        measurement: this.component.measurement,
      }*/
      ).then((res) => {
        console.log(res);
        this.$emit('delete', this.component.id);
      }).finally(() => {
        this.loading = false;
        this.handleClose();
      });
    },
  },

};
</script>

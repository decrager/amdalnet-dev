<template>
  <el-dialog :title="'Identitas'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <div class="form-container">
      <div><img :src="feedback.photo_filepath" width="400px"></div>
      <div>Nama: {{ feedback.name }}</div>
      <div>NIK: {{ feedback.id_card_number }}</div>
      <div>No. Telepon: {{ feedback.phone }}</div>
      <div>Email: {{ feedback.email }}</div>
      <el-button
        type="danger"
        size="mini"
        icon="el-icon-circle-close"
        @click="closeDialog()"
      >
        Tutup
      </el-button>
    </div>
  </el-dialog>
</template>
<script>

export default {
  name: 'IdentityDialog',
  props: {
    feedback: {
      type: Object,
      default: () => {},
    },
    show: Boolean,
  },
  data(){
    return {
      data: {},
    };
  },
  methods: {
    closeDialog() {
      this.show = false;
    },
  },
};
</script>

<template>
  <div>
    <el-table
      :data="feedbacks"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="ID" width="40">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Tanggal Dibuat">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Nama">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Peran">
        <template slot-scope="scope">
          <span>{{ scope.row.responder_type_name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Saran/Pendapat/Tanggapan">
        <template slot-scope="scope">
          <span v-html="scope.row.concern" />
        </template>
      </el-table-column>
      <el-table-column align="center" label="Relevansi">
        <template slot-scope="scope">
          <el-select
            v-model="scope.row.is_relevant"
            placeholder="Select"
            style="width: 100%"
            @change="onChangeRelevant(scope.row.id, $event)"
          >
            <el-option
              v-for="item in relevantChoices"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Identitas">
        <template slot-scope="scope">
          <el-button
            type="info"
            size="mini"
            icon="el-icon-view"
            @click="showIdentity(scope.row.id)"
          >
            Lihat Identitas
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <IdentityDialog
      :data="identityDialogData"
      :photo="identityDialogImg"
      :show="showIdDialog"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import IdentityDialog from './IdentityDialog.vue';
const feedbackResource = new Resource('feedbacks');

export default {
  name: 'FeedbackList',
  components: { IdentityDialog },
  props: {
    announcement: {
      type: Object,
      default: () => {},
    },
    feedbacks: {
      type: Array,
      default: () => [],
    },
    responderTypes: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      relevantChoices: [],
      selectedFeedback: {},
      identityDialogData: [],
      identityDialogImg: '',
      showIdDialog: false,
    };
  },
  mounted() {
    this.relevantChoices = [
      { value: true, label: 'Relevan' },
      { value: false, label: 'Tidak Relevan' },
    ];
    // event handler
    // this.$bus.$on('updateFeedbackList', event => {
    //   this.getFeedbacks(this.id);
    // });
  },
  methods: {
    showIdentity(feedbackId) {
      var toShow = {};
      this.feedbacks.map((item) => {
        if (item.id === feedbackId){
          toShow = item;
        }
      });
      this.selectedFeedback = toShow;
      this.identityDialogImg = toShow.photo_filepath;
      this.identityDialogData = [
        {
          param: 'Nama',
          value: this.selectedFeedback.name,
        },
        {
          param: 'NIK',
          value: this.selectedFeedback.id_card_number,
        },
        {
          param: 'No. Telepon',
          value: this.selectedFeedback.phone,
        },
        {
          param: 'Email',
          value: this.selectedFeedback.email,
        },
      ];
      this.showIdDialog = true;
    },
    async onChangeRelevant(feedbackId, event) {
      var toUpdate = {};
      this.feedbacks.map((item) => {
        if (item.id === feedbackId){
          toUpdate = item;
        }
      });
      feedbackResource
        .update(feedbackId, toUpdate)
        .then(response => {
          const is_relevant_str = response.data.is_relevant ? 'RELEVAN' : 'TIDAK RELEVAN';
          const message = 'SPT \'' + response.data.name + '\' berhasil diupdate sebagai ' + is_relevant_str;
          this.$message({
            message: message,
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
        });
    },
  },
};
</script>

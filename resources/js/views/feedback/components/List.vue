<template>
  <div style="margin-bottom: 20px;">
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
      <el-table-column align="left" label="Tanggal Dibuat">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Nama">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Peran">
        <template slot-scope="scope">
          <span>{{ scope.row.responder_type_name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Kekhawatiran">
        <template slot-scope="scope">
          <span v-html="scope.row.concern" />
        </template>
      </el-table-column>
      <el-table-column align="left" label="Harapan">
        <template slot-scope="scope">
          <span v-html="scope.row.expectation" />
        </template>
      </el-table-column>
      <el-table-column align="left" label="Rating">
        <template slot-scope="scope">
          <el-rate
            v-model="scope.row.rating"
            :colors="['#99A9BF', '#F7BA2A', '#F7BA2A', '#F7BA2A', '#FF9900']"
            :max="5"
            style="margin-top:8px;"
            @change="onChangeForm(scope.row.id, $event)"
          />
        </template>
      </el-table-column>
      <el-table-column align="left" label="Relevansi">
        <template slot-scope="scope">
          <el-select
            v-model="scope.row.is_relevant"
            placeholder="Select"
            style="width: 100%"
            @change="onChangeForm(scope.row.id, $event)"
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
      <el-table-column align="left" label="Identitas">
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
    async onChangeForm(feedbackId, event) {
      var toUpdate = {};
      this.feedbacks.map((item) => {
        if (item.id === feedbackId){
          toUpdate = item;
        }
      });
      feedbackResource
        .update(feedbackId, toUpdate)
        .then(response => {
          const message = 'SPT \'' + response.data.name + '\' berhasil diupdate';
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

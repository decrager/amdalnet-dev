<template>
  <div style="margin-bottom: 20px;">
    <div align="right">
      <el-button
        type="success"
        size="mini"
        icon="el-icon-plus"
        @click="showCreateFeedback()"
      >
        Tambah SPT Baru
      </el-button>
    </div>
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
      <el-table-column align="left" label="Rating" width="150">
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
    <CreateFeedback
      :feedback="feedback"
      :show="showFeedback"
      :announcement-id="announcementId"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import IdentityDialog from './IdentityDialog.vue';
import CreateFeedback from '@/views/home/components/CreateFeedback.vue';
const feedbackResource = new Resource('feedbacks');

export default {
  name: 'FeedbackList',
  components: { IdentityDialog, CreateFeedback },
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
    const id = parseInt(this.$route.params && this.$route.params.id);
    return {
      announcementId: id,
      feedback: {},
      relevantChoices: [],
      selectedFeedback: {},
      identityDialogData: [],
      identityDialogImg: '',
      showIdDialog: false,
      showFeedback: false,
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
    showCreateFeedback() {
      this.showFeedback = true;
    },
    showIdentity(feedbackId) {
      const toShow = this.feedbacks.find(f => f.id === feedbackId);
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
      const toUpdate = this.feedbacks.find(f => f.id === feedbackId);
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

<template>
  <div style="margin-bottom: 20px;">
    <div class="filter-container">
      <el-row type="flex" class="row-bg" justify="space-between">
        <el-button
          v-if="!isExaminer"
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="showCreateFeedback()"
        >
          Tambah SPT Baru
        </el-button>
      </el-row>
    </div>
    <el-table
      :key="showFeedback"
      v-loading="loading"
      :data="feedbacks"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    >
      <el-table-column type="expand" class="row-detail">
        <template slot-scope="scope">
          <div class="post">
            <div class="entity-block" />
            <span class="action pull-right">
              <el-button
                href="#"
                type="text"
                icon="el-icon-view"
                @click="showIdentity(scope.row.id)"
              >
                View Identity
              </el-button>
            </span>
            <p><b>Kekhawatiran:</b></p>
            <div class="description" v-html="scope.row.concern" />
            <p style="margin-top: 10px;"><b>Harapan:</b></p>
            <div class="description" v-html="scope.row.expectation" />
          </div>
        </template>
      </el-table-column>
      <el-table-column label="No." width="54px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Tanggal" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Nama">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Peran" width="260">
        <template slot-scope="scope">
          <span>{{ scope.row.responder_type_name }}</span>
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
      <el-table-column align="left" label="Relevansi" width="130">
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
    </el-table>
    <IdentityDialog
      :data="identityDialogData"
      :photo="identityDialogImg"
      :show="showIdDialog"
      @handleCloseDialog="handleCloseIdentityDialog"
    />
    <CreateFeedback
      :feedback="feedback"
      :show="showFeedback"
      :announcement-id="announcementId"
      @handleCloseDialog="handleCloseFeedbackDialog"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import IdentityDialog from './IdentityDialog.vue';
import CreateFeedback from '@/views/home/components/CreateFeedback.vue';
const feedbackResource = new Resource('feedbacks');
const responderTypeResource = new Resource('responder-types');

export default {
  name: 'FeedbackList',
  components: { IdentityDialog, CreateFeedback },
  data() {
    return {
      feedbacks: [],
      loading: true,
      announcementId: 0,
      feedback: {},
      relevantChoices: [],
      selectedFeedback: {},
      identityDialogData: [],
      identityDialogImg: '',
      showIdDialog: false,
      showFeedback: false,
      userInfo: {},
    };
  },
  computed: {
    isExaminer() {
      return this.$store.getters.roles.includes('examiner');
    },
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
    this.getFeedbacks();
  },
  methods: {
    async getFeedbacks(){
      const id = parseInt(this.$route.params && this.$route.params.id);
      this.announcementId = id;
      // filter by project ID
      this.loading = true;
      const { data } = await feedbackResource.list({
        announcement_id: id,
        deleted: false,
      });
      const responderTypes = await responderTypeResource.list({});
      const responder_types = responderTypes.data;
      data.map((item) => {
        const key = item.responder_type_id;
        var responder_type_name = '';
        responder_types.map((item) => {
          if (item.id === key){
            responder_type_name = item.name;
          }
        });
        item.responder_type_name = responder_type_name;
        item.is_relevant_str = item.is_relevant ? 'Relevan' : 'Tidak Relevan';
      });
      this.feedbacks = data;
      this.loading = false;
    },
    showCreateFeedback() {
      this.showFeedback = true;
      this.showIdDialog = false;
    },
    handleCloseFeedbackDialog(){
      this.showFeedback = false;
      this.showIdDialog = false;
      this.getFeedbacks();
    },
    handleCloseIdentityDialog(){
      this.showFeedback = false;
      this.showIdDialog = false;
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
      this.showFeedback = false;
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

<template>
  <div style="padding: 2em;" :loading="loading">
    <template v-if="activities.length > 0">

      <el-button type="info" :icon="reverse? 'el-icon-bottom': 'el-icon-top' " style="float:right;" circle @click="reverse = !reverse" />
      <div style="margin-top:1em;"><el-checkbox v-model="showAll">Tampilkan seluruh tahapan</el-checkbox></div>
      <el-timeline :reverse="reverse" style="margin-top: 3em;">
        <el-timeline-item
          v-for="(activity, index) in showAll ? activities : activities.filter(a => a.rank <= current_rank)"
          :key="index"
          :timestamp="activity.datetime"
          size="large"
          :type="activity.rank <= current_rank ? (activity.rank === 1? 'info':'primary') : 'default'"
          placement="top"
        >
          <div>
            <p>{{ activity.label || activity.to_place }} <br> <span v-if="activity.username" style="font-size:80%;">oleh {{ activity.username }}</span> </p>
          </div>
        </el-timeline-item>
      </el-timeline>
    </template>
    <template v-else>
      <div>
        <p>Data tidak ditemukan</p>
      </div>
    </template>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const timelineResource = new Resource('timeline');
export default {
  name: 'ProjectTimeline',
  props: {
    id: {
      type: Number,
      default: 0,
    },
    marking: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      // id: 0,
      reverse: false,
      activities: [],
      loading: false,
      current_rank: 0,
      showAll: false,
    };
  },
  mounted(){
    // this.id = this.project_id = this.$route.params && this.$route.params.id;
    this.getData();
  },
  methods: {
    async getData() {
      this.activities = [];
      this.loading = true;
      this.current_rank = 0;
      await timelineResource.list({ id: this.id })
        .then((res) => {
          this.activities = this.process(res);

          if ((this.activities.length > 0) && (this.marking !== null)){
            const state = this.activities.find(s => s.to_place === this.marking);
            this.current_rank = state.rank;
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    process(data){
      return data;
      /*
      const res = [];

      data.map((e) => {
        const val = JSON.parse(e.new_values);
        if ((e.event === 'created') || ((e.event === 'updated') && (val.marking))){
          const processed = {
            datetime: e.datetime,
            username: e.user_name,
            event: e.event,
            content: e.new_values,
            value: val,
          };
          res.push(processed);
        }
      });
      return res;*/
    },
  },
};
</script>

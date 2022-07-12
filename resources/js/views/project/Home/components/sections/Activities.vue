<template>
  <div>
    <ol v-if="data.length > 0" class="ph-activities">
      <li v-for="pr in data" :key="'pr_u_'+ pr.id" class="data"><strong>{{ pr.kbli + ' - ' + pr.sector_name }}</strong>:  {{ pr.name }} ({{ pr.scale + ' ' + pr.scale_unit }})</li>
    </ol>
    <p v-else class="data">-</p>
  </div>
</template>
<script>
export default {
  name: 'ProjectActivities',
  props: {
    data: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
};
</script>
<style lang="scss" scoped>
p.data + p.data {
  margin: 0 !important;
}
ul.ph-activities{
  list-style-type: none;
  margin-left: -1em;
  li {

  }
}

</style>

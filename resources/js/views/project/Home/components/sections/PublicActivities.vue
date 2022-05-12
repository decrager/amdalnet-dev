<template>
  <div>
    <ul v-if="data.length > 0" class="ph-activities">
      <template v-for="pr in data">
        <li :key="'pr_u_'+ pr.id" class="data">{{ pr.kbli + ' - ' + pr.sector_name }}:  {{ pr.name }} ({{ pr.scale + ' ' + pr.scale_unit }})</li>
      </template>
    </ul>
    <p v-else class="data">-</p>
  </div>
</template>
<script>
export default {
  name: 'PublicProjectActivities',
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


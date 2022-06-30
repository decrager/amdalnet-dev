<template>
  <div>
    <p style="font-weight:bold;">Hasil Konsultasi Publik</p>
  </div>
</template>
<script>
// import Resource from '@/api/resource';
// const ppcResource = new Resource('public-consultations');
export default {
  name: 'ProjectPublicConsultation',
  props: {
    id: {
      type: Number,
      default: 0,
    },
  },
};
</script>

<template>
  <div class="ph-formulator">
    <p class="header">Ketua</p>
    <formulator-persons :data="ketua" />

    <p class="header">Anggota</p>
    <formulator-persons :data="anggota" />

    <p class="header">Tim Ahli</p>
    <formulator-persons :data="ahli" />

  </div>
</template>
<script>
import FormulatorPersons from './sections/Formulators.vue';
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulator-teams');
export default {
  name: 'ProjectFormulatorTeam',
  components: { FormulatorPersons },
  prop: {
    id: {
      type: Number,
      default: 0,
    },
  },
  data(){
    return {
      data: [],
      ketua: [],
      anggota: [],
      ahli: [],
    };
  },
  mounted(){
    // this.id = this.$route.params && this.$route.params.id;
    this.getFormulator();
    this.getExpert();
  },
  methods: {
    async getFormulator(){
      this.data = [];
      // console.log('Get Formulator', this.id);
      const projectId = this.$route.params && this.$route.params.id;
      await formulatorResource.list({ type: 'tim-penyusun', idProject: projectId })
        .then((res) => {
          this.data = res;
          this.ketua = this.data.filter((e) => e.position === 'Ketua');
          this.anggota = this.data.filter((e) => e.position === 'Anggota');
          // console.log('ketua: ', this.ketua);
          // console.log('anggota: ', this.anggota);
        })
        .finally();
    },
    async getExpert(){
      this.ahli = [];
      const projectId = this.$route.params && this.$route.params.id;
      await formulatorResource.list({ type: 'tim-penyusun', idProject: projectId })
        .then((res) => {
          this.ahli = res;
          // console.log('ahli: ', this.ahli);
        })
        .finally();
    },
  },
};
</script>
<style lang="scss" scoped>
.ph-formulator{
  padding: 2em;
}
</style>

<template>
  <div class="ph-initiator ph">
    <div class="initiator-header">
      <div style="width: 150px; height: 150px; margin: 1em auto;">
        <el-image
          style="width: 150px; height: 150px; margin: 1em auto;"
          :src="data.logo"
          fit="contain"
        >
          <div slot="error" class="image-slot">
            <i class="el-icon-picture-outline" style="font-size: 500%; line-height: 160%;" />
          </div>
        </el-image>
      </div>

      <p class="ph-header" style="border:none;">{{ data.name }}</p>
      <p>{{ data.address }}</p>
      <p>{{ data.district || '' }}
        {{ data.province || '' }}</p>
    </div>
    <div>
      <p class="ph-header"><i class="el-icon-user-solid" /> Penanggung Jawab</p>
      <p class="data">{{ data.pic || '' }}</p>
    </div>
    <div>
      <p class="ph-header"><i class="el-icon-phone" /> No. Telpon</p>
      <p class="data">{{ data.phone || '' }}</p>
    </div>
    <div>
      <p class="ph-header"><i class="el-icon-message" /> Alamat email</p>
      <p class="data" style="word-break: break-all;">{{ data.email || '' }}</p>
    </div>

  </div>
</template>
<script>
export default {
  name: 'PublicProjectInitiator',
  props: {
    data: {
      type: Object,
      default: null,
    },
  },
  mounted() {

  },
  methods: {

  },
};
</script>


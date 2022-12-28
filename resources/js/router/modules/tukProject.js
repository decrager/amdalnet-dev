import Layout from '@/layout';

const tukProjectRoutes = {
  path: '/tuk-project',
  component: Layout,
  meta: {
    title: 'Daftar Kegiatan',
    icon: 'layout-fluid',
    permissions: ['view menu tuk project'],
  },
  alwaysShow: true,
  children: [
    {
      path: '',
      component: () => import('@/views/tuk-project/index.vue'),
      name: 'tukProject',
      meta: {
        title: 'Semua Kegiatan',
        icon: 'layout-fluid',
        noCache: true,
        permissions: ['view menu tuk project'],
      },
    },
    {
      path: '/listNotAssignMember',
      component: () => import('@/views/tuk-project/listNotAssignMember.vue'),
      name: 'tukProject',
      meta: {
        title: 'Kegiatan belum diproses',
        icon: 'layout-fluid',
        noCache: true,
        permissions: ['view menu tuk project'],
      },
    },
    {
      path: '/listAssignMember',
      component: () => import('@/views/tuk-project/listAssignMember.vue'),
      name: 'tukProject',
      meta: {
        title: 'Kegiatan sudah diproses',
        icon: 'layout-fluid',
        noCache: true,
        permissions: ['view menu tuk project'],
      },
    },
    // {
    //   path: '/listAssignMemberDisProv',
    //   component: () => import('@/views/tuk-project/listAssignMemberDisProv.vue'),
    //   name: 'tukProject',
    //   meta: {
    //     title: 'Kegiatan Kewenangan Daerah',
    //     icon: 'layout-fluid',
    //     noCache: true,
    //     permissions: ['view menu tuk project'],
    //   },
    // },
    {
      path: ':id(\\d+)/member',
      component: () =>
        import('@/views/tuk-project/components/AssignMember.vue'),
      name: 'tukProjectMember',
      hidden: true,
      meta: {
        title: 'Daftar Kegiatan',
        icon: 'layout-fluid',
        noCache: true,
        permissions: ['manage tuk project'],
      },
    },
  ],
};

export default tukProjectRoutes;

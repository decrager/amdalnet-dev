import Layout from '@/layout';

const tukProjectRoutes = {
  path: '/tuk-project',
  component: Layout,
  meta: {
    title: 'Daftar Kegiatan',
    icon: 'layout-fluid',
    permissions: ['view menu tuk project'],
  },
  children: [
    {
      path: '',
      component: () => import('@/views/tuk-project/index.vue'),
      name: 'tukProject',
      meta: {
        title: 'Daftar Kegiatan',
        icon: 'layout-fluid',
        noCache: true,
        permissions: ['view menu tuk project'],
      },
    },
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

import Layout from '@/layout';

const tukProjectRoutes = {
  path: '/tuk-project',
  component: Layout,
  meta: {
    title: 'Daftar Kegiatan',
    icon: 'layout-fluid',
    roles: ['examiner-secretary'],
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
        roles: ['examiner-secretary'],
      },
    },
    {
      path: ':id(\\d+)/member',
      component: () =>
        import('@/views/tuk-project/components/AssignMember.vue'),
      name: 'tukProjectMember',
      meta: {
        title: 'Daftar Kegiatan',
        icon: 'layout-fluid',
        noCache: true,
        roles: ['examiner-secretary'],
      },
    },
  ],
};

export default tukProjectRoutes;

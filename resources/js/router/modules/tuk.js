import Layout from '@/layout';

const tukRoutes = {
  path: '/tuk',
  component: Layout,
  meta: {
    title: 'tuk',
    icon: 'zip',
    permissions: ['view menu tuk management'],
  },
  children: [
    {
      path: '',
      component: () => import('@/views/tuk-management/index.vue'),
      name: 'tuk',
      meta: {
        title: 'Manajemen TUK',
        icon: 'id-badge',
        noCache: true,
        permissions: ['view menu tuk management'],
      },
    },
    {
      path: 'create',
      component: () => import('@/views/tuk-management/Create'),
      name: 'createTuk',
      hidden: true,
      meta: {
        title: 'Tambah TUK',
        icon: 'documentation',
        noCache: true,
        permissions: ['manage tuk management'],
      },
    },
    {
      path: 'edit/:id',
      component: () => import('@/views/tuk-management/Create'),
      name: 'editTuk',
      hidden: true,
      meta: {
        title: 'Edit TUK',
        icon: 'documentation',
        noCache: true,
        permissions: ['manage tuk management'],
      },
    },
    {
      path: ':id/anggota',
      component: () => import('@/views/tuk-management/TeamMember'),
      name: 'kelolaTuk',
      hidden: true,
      meta: {
        title: 'Kelola TUK',
        icon: 'documentation',
        noCache: true,
        permissions: ['manage tuk management'],
      },
    },
  ],
};

export default tukRoutes;

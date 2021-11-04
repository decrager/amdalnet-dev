import Layout from '@/layout';

const masterRoutes = {
  path: '/master-data',
  component: Layout,
  alwaysShow: true,
  meta: {
    title: 'masterData',
    icon: 'el-icon-reading',
    permissions: ['view menu administrator'],
  },
  children: [
    {
      path: '/provinces',
      component: () => import('@/views/master-data/province'),
      name: 'province',
      hidden: true,
      meta: { title: 'provinsi', icon: 'el-icon-school' },
    },
  ],
};

export default masterRoutes;

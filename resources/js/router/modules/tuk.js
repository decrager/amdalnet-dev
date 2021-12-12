import Layout from '@/layout';

const tukRoutes = {
  path: '/tuk',
  component: Layout,
  meta: { title: 'tuk', icon: 'zip', permissions: ['view menu examiner team'] },
  children: [
    {
      path: '',
      component: () => import('@/views/error-page/404'),
      name: 'tuk',
      meta: { title: 'tuk', icon: 'id-badge', noCache: true, permissions: ['view menu examiner team'] },
    },
  ],
};

export default tukRoutes;

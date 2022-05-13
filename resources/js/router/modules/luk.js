import Layout from '@/layout';

const lukRoutes = {
  path: '/luk',
  component: Layout,
  meta: { title: 'luk', icon: 'zip', permissions: ['view menu luk'] },
  children: [
    {
      path: '',
      component: () => import('@/views/error-page/404'),
      name: 'luk',
      meta: { title: 'luk', icon: 'badge', noCache: true, permissions: ['view menu luk'] },
    },
  ],
};

export default lukRoutes;

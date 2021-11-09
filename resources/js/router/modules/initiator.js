import Layout from '@/layout';

const initiatorRoutes = {
  path: '/initiator',
  component: Layout,
  meta: { title: 'initiator', icon: 'user', permissions: ['view menu initiator'] },
  children: [
    {
      path: '',
      component: () => import('@/views/error-page/404'),
      name: 'initiator',
      meta: { title: 'initiator', icon: 'user', noCache: true, permissions: ['view menu initiator'] },
    },
  ],
};

export default initiatorRoutes;

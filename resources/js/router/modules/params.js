import Layout from '@/layout';

const paramRoutes = {
  path: '/params',
  component: Layout,
  name: 'Params',
  children: [
    {
      path: '',
      component: () => import('@/views/params/index'),
      name: 'Params',
      meta: { title: 'Params', icon: 'user', noCache: true },
    },
    {
      path: 'createParams',
      component: () => import('@/views/params/Create'),
      name: 'createParams',
      hidden: true,
      meta: { title: 'addParams', icon: 'documentation', noCache: true },
    },
  ],
};

export default paramRoutes;

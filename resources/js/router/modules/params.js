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
      path: 'create-params',
      component: () => import('@/views/params/Create'),
      name: 'addParams',
      hidden: true,
      meta: { title: 'Tambah Parameter Aplikasi', icon: 'documentation', noCache: true },
    },
    {
      path: 'update-params',
      component: () => import('@/views/params/Create'),
      name: 'updateParams',
      hidden: true,
      meta: { title: 'Tambah Parameter Aplikasi', icon: 'documentation', noCache: true },
    },

  ],
};

export default paramRoutes;

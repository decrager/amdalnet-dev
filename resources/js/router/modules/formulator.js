import Layout from '@/layout';

const formulatorRoutes = {
  path: '/formulator',
  component: Layout,
  meta: { title: 'formulator', icon: 'documentation', permissions: ['view menu lpjp', 'view menu formulator expert', 'view menu formulator team'] },
  children: [
    {
      path: 'lpjp',
      component: () => import('@/views/lpjp/index'),
      name: 'lpjp',
      meta: { title: 'lpjp', icon: 'documentation', noCache: true, permissions: ['view menu lpjp'] },
    },
    {
      path: 'lpjp/create',
      component: () => import('@/views/lpjp/Create'),
      name: 'createLpjp',
      hidden: true,
      meta: { title: 'Tambah LPJP', icon: 'documentation', noCache: true },
    },
    {
      path: 'lpjp/edit/:id',
      component: () => import('@/views/lpjp/Create'),
      name: 'editLpjp',
      hidden: true,
      meta: { title: 'Edit LPJP', icon: 'documentation', noCache: true },
    },
    {
      path: 'expert',
      component: () => import('@/views/formulator/index'),
      name: 'formulatorExpert',
      meta: { title: 'formulatorExpert', icon: 'user', noCache: true, permissions: ['view menu formulator expert'] },
    },
    {
      path: 'expert/create',
      component: () => import('@/views/formulator/Create'),
      name: 'createFormulator',
      hidden: true,
      meta: { title: 'addFormulator', icon: 'user', noCache: true },
    },
    {
      path: 'expert/edit/:id',
      component: () => import('@/views/formulator/Create'),
      name: 'editFormulator',
      hidden: true,
      meta: { title: 'editFormulator', icon: 'user', noCache: true },
    },
    {
      path: 'team',
      component: () => import('@/views/error-page/404'),
      name: 'formulatorTeam',
      meta: { title: 'formulatorTeam', icon: 'user', noCache: true, permissions: ['view menu formulator team'] },
    },
  ],
};

export default formulatorRoutes;

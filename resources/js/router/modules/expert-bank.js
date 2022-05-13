import Layout from '@/layout';

const expertBankRoutes = {
  path: '/bank-ahli',
  component: Layout,
  meta: { title: 'expertBank', icon: 'graduation-cap', permissions: ['view menu expert'] },
  children: [
    {
      path: '',
      component: () => import('@/views/expert-bank/index'),
      name: 'expertBank',
      meta: { title: 'expertBank', icon: 'graduation-cap', noCache: true, permissions: ['view menu expert'] },
    },
    {
      path: 'create',
      component: () => import('@/views/expert-bank/Create'),
      name: 'createExpertBank',
      hidden: true,
      meta: { title: 'createExpertBank', icon: 'documentation', noCache: true },
    },
    {
      path: 'edit/:id',
      component: () => import('@/views/expert-bank/Create'),
      name: 'editExpertBank',
      hidden: true,
      meta: { title: 'editExpertBank', icon: 'documentation', noCache: true },
    },
  ],
};

export default expertBankRoutes;
